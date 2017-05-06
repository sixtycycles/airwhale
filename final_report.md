# Final Projects Requirements and Report Outline

  >**Citizen Non-Emergency Problem Reporter Application**<br/>
  *Roderic O’Connor, Jon Cole & Tara Mullen*
  <br/> SIE-510 May 4th 2017

***
### Introduction:
Our project is a web application developed for the Town of Orono. The problem we are hoping to address is how non emergency issues were reported to the Town of Orono. These problems do not fall within any one town department and it can be difficult to track the status of any actions taken. Typical problems include potholes, graffiti, malfunctioning street lamps, etc.

Our group worked to develop a web application that would allow citizens to report issues by adding the geographic location of the problems as points on a map of the town of Orono. The town currently has no centralized system for reporting these type of issue. They experience duplicate individuals calling the offices, as well as unknown status of problems’ repairs. Our application attempts to remedy these issues, and to provide a easily maintainable  and implementable solution for the town.

#### This project has two separate user types:

  > **Citizens** who need a way to communicate with the Town about issues that are pressing but do not fall under the responsibilities of emergency services.
  **Administrators**, Town of Orono employees who need a way to organize and track complaints about issues in the town to effectively plan repairs.

Our application provides a portable, easily maintained and open source solution for the town to implement, that should alleviate this issue, and provide historical data to the town for tracking completion times, reporting frequency and problem “hot spots”

The requirements as outlined by the client:
* A web page citizens could access a map of Orono
* People can report issues under different categories
* Reported issues with show up as pins
* Each pin has attributes (including: type, date, person who reported, etc)
* Each issue can be like to draw attention to the problem
* Emails will be verified during the report process
* Upload images of the Problems

This application attempts facilitate communication between citizens and the Town of Orono. With a developed reporting system that allows the town employees to view, organize and track the problems the Town should have few problems planning their response to the issues in Orono.
***
### Conceptual model: ###




Description of data sources - Discuss how or from where data were collected or generated if simulated, and discuss any relevant data quality issues.

* [ME GIS website](http://www.maine.gov/megis/catalog/)
  * Orono roads polygon adapted from [911RDS](https://geolibrary-maine.opendata.arcgis.com/datasets/a7024b7148634880bf1bba0f3cff8848_1?geometry=-83.388%2C42.935%2C-54.56%2C47.572)
  * Town of orono outline, adapted from (METWP24) at Maine office of GIS [METWP24](http://www.maine.gov/megis/catalog/shps/state/MEGIS_Town_Bdys.zip)
* [Google Maps](https://developers.google.com/maps/)
  * Basemap, centered on Orono Maine.
  * [Javascript API](https://developers.google.com/maps/documentation/javascript/3.exp/reference.)
* [Town of Orono](http://orono.org)
  * Outline of project requirements, interface mockup.(*c/o Avinash Rude, Town of Orono*)
  * [Contact information](http://orono.org/149/Departments) for various town officials




<!-- description of your methodology -  Describe the processing steps required for your project development, your analysis steps and report results.
Original - listened to client describe the issue
Interpreted client’s description to develop rough outline of the application
Met with Professor to discuss development of the application
Email client with follow up questions to gather more information -->
***
### Methodology: ###

   The initial project outline given in our meeting with the Town planners served as our starting point for this application. We compiled a shortlist of functionality the application required and began the process of examining technologies to use. We determined that for the functionality required in this project, ArcGis Online was not the best solution as, while it met many of the needs of the project, and also had the advantage of keeping the solution within the software ecosystem that the Town currently utilizes, the effort to conform the tools available to suit the needs of the project was too great. We ultimately settled on using free web mapping API’s, and hosting the application on the Town’s server. We would create a tool to migrate the data from the application to a compatible format for the town to address the incompatibility issues by this solution.
   Once a technology stack had been selected, we reached out to the town again, to elicit a more comprehensive explanation of the desired functionality, and to gain a better understanding of the client’s wishes and expectations.

   We broke these down into sections of the code that would have to be implemented. We also began work on the conceptual model (the first iteration) at this point. We started with a single database table to hold a problem, learned how to display and extract user interactions with the Google Maps Javascript API, and some PHP code to Communicate between the two.

   Once this initial function was modeled, we focused on adding the user registration functionality. Using PHP_Mailer, and a throwaway Gmail account, we were able to configure a user registration system, based on several examples we saw in online searching. Since the application does not contain sensitive personal information, and the only personally identifiable information stored is an email address and a user name, and any content the users upload to this public forum, we determined that this level of security was sufficient for this application.

   Users register to use the site, and each registration must have a unique Email address. By doing this, we attempt to prevent spamming the reporter with issues, and also to have some minimum contact information for a problem submitter. There are two types of user, a generic user, and a user with administrative privileges. Since town officers may wish to enter information themselves, we simply add administrative functionality to their account at the database level. All other users have access only the the problem submission view.

   We added functionality to the problem submission page in a modular way, first adding a  feature, then testing it. At a successful testing, a new function was added. Periodically in this process, we restructured the site and conceptual model as the application grew in complexity. Ability to upload images of the problem, as well as to filter the problems by type were added. For the administrative view, we added the functionality for users to enter starting, completing and deleting problems (in case a user submits a duplicate issue, or an inappropriate issue).

   We divided some of the labor up by the group member’s specialization, but kept a regular slack channel for communication between group members.  Periodically we would check back in with the town to ensure that the project was conforming to their expectation.

   We used Github to host the version control repository, so that we could all work on the same version of the project and not overwrite each others functionality, or duplicate effort.

***
### Issues ###

* **KML layer for streets does not line up with basemap**
  > We think this is a projection issue. We referenced google maps' documentation for what projectrion and coordinate system is in use, and adjusted the file accordingly. It is possible it is a data quality issue, or that some functionality in google maps interpolates the data for display purposes in some way to effect this issue.
* **Add download images to CSV, change to Excel**
  > The images of the problems are not included in the downloaded dataset, as the CSV file format does not allow for their inclusion. we are looking at switching to a full Excel file type.
* **Give admins more functionality**
  >   we feel that these features will help with some frustrating administrative functions and help to correct features we intend to add are:

  * **reset problem start/complete times.**
  *Right now these are entered as the time of change, but an admin cannot backdate the issues' timestamp.*
  * **reset likes count.**
  * **edit problem description.** *incase of editing needed*
  * **edit problem type.**
***
### Conclusions ###

***
### References ###

“ArcGIS Pro Tool reference—ArcGIS Pro | ArcGIS Desktop.” Accessed May 6, 2017. http://pro.arcgis.com/en/pro-app/tool-reference/main/arcgis-pro-tool-reference.htm.

“Components · Bootstrap.” Accessed May 6, 2017. http://getbootstrap.com/components/.
“Google Maps JavaScript API V3 Reference | Google Maps JavaScript API.” Google Developers. Accessed May 6, 2017. https://developers.google.com/maps/documentation/javascript/3.exp/reference.
“PHPMailer/PHPMailer.” GitHub. Accessed May 6, 2017. https://github.com/PHPMailer/PHPMailer.

ESRI 2011. ArcGIS Desktop: Release 10.3 Redlands, CA: Environmental Systems Research Institute.

Khodke, Pradeep. “Easy Ajax Image Upload with jQuery, PHP | Coding Cage.” Accessed May 6, 2017. http://www.codingcage.com/2015/12/easy-ajax-image-upload-with-jquery-php.html.

Khodke, Pradeep. “How to Send HTML Format eMails in PHP Using PHPMailer | Coding Cage.” Accessed May 6, 2017. http://www.codingcage.com/2016/03/how-to-send-html-emails-in-php-with.html.

***
### [Appendix A: Python Code](https://github.com/sixtycycles/airwhale/blob/rework/python/arcmap_import_tool.py) ###
*click title for link to most recent version.*

```
'''
Author:        Tara Mullen & Rod O'Connor
Version:       ArcGIS 10.4
Project:       Citizen none-emergancy reporting
Required Arguements: csv file
Description: This program turns a cvs file into a shapefile headings are in:
"PROBLEM_ID,USERNAME,LATTITUDE,LONGITUDE,DESCRIPTION,TYPE,SUBMIT_DATETIME,STATUS,IMAGE_NAME"
'''

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
```
