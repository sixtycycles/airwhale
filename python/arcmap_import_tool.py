'''----------------------------------------------------------------------------------
Author:        Tara Mullen & Rod O'Connor
Version:       ArcGIS 10.4
Project:       Citizen none-emergancy reporting
Required Arguements: csv file
Description: This program turns a cvs file into a shapefile headings are in:
            "PROBLEM_ID,USERNAME,LATTITUDE,LONGITUDE,DESCRIPTION,TYPE,SUBMIT_DATETIME,STATUS,IMAGE_NAME"
            order
-------------------------------------------------------------------------------'''
import arcpy
import re, os
from arcpy import env
from arcpy.sa import*
from datetime import datetime
import csv
#Spatial Reference WGS 84
sr = arcpy.SpatialReference(4326)

arcpy.env.workspace="C:\SIE_510_Python\Non_Emer_Project"
arcpy.CheckOutExtension('Spatial')
arcpy.env.overwriteOutput = True

#Create feature class to write out to
in_file = arcpy.GetParameterAsText(0)
out_file = arcpy.GetParameterAsText(1)

out_filename = os.path.basename(out_file)
out_path = os.path.dirname(out_file)
#create output shapefile
arcpy.CreateFeatureclass_management(out_path,out_filename,"Point",spatial_reference=sr)

#add fields to hold each column
arcpy.AddField_management(out_file,"Problem_ID","SHORT")
arcpy.AddField_management(out_file,"Username","TEXT",20)
arcpy.AddField_management(out_file,"Descr","TEXT",100)
arcpy.AddField_management(out_file,"Type","TEXT",30)
arcpy.AddField_management(out_file,"Crt_Date","TEXT",20)
arcpy.AddField_management(out_file,"Strt_Date","TEXT",20)
arcpy.AddField_management(out_file,"Comp_Date","TEXT",20)
arcpy.AddField_management(out_file,"Status","TEXT",10)
arcpy.AddField_management(out_file,"Likes","TEXT",50)


#csv file setup
csvfile = open(in_file,'r')
reader = csv.reader(csvfile)
#ditch the headers
headers = next(reader)

#create array to hold points
pointList = arcpy.Array()

#List of filds for Insert Cursor to use
fields = ['SHAPE@','Problem_ID','Username','Descr','Type','Crt_Date','Strt_Date','Comp_Date','Status','Likes']
#PROBLEM_ID,USERNAME,LATTITUDE,LONGITUDE,DESCRIPTION,STATUS,CREATE_DATETIME,START_DATETIME,COMPLETE_DATETIME,LIKES,PROBLEM_TYPE
insertcursor = arcpy.da.InsertCursor(out_file,fields)

#Split file into segments
for row in reader:

    problem_ID = row[0]
    username = row[1]
    descr = re.sub(r'"','',row[4])
    status = row[5]
    # crt_date= datetime.strptime(row[6],"%m/%d/%y %H:%M")
    # strt_date= datetime.strptime(row[7],"%m/%d/%y %H:%M") or ''
    # comp_date= datetime.strptime(row[8],"%m/%d/%y %H:%M") or ''
    crt_date = row[6]
    strt_date = row[7]
    comp_date = row[8]
    likes = row[9]
    type = row[10]

#x,y coordinates in Lat/Long (x = longitude, y = latitude)
#Latitude
    lat=float(row[2].strip())
#Longitude

    long=float(row[3].strip())
#Create points
#Append points to the pointList
    point = arcpy.Point(long,lat)
    point_obj = arcpy.PointGeometry(point,sr)
    print point


# insert attriubtes into the attribute table
    row =[point_obj,problem_ID,username,descr,type,crt_date,strt_date,comp_date,status,likes]
    insertcursor.insertRow(row)

del insertcursor
