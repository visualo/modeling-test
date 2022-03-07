Instructions:
1/ I modified the structure of request-data.json, adding an array to transformObject in the FILTER type, assuming we have more than one condition!
2/ I created classes for all TYPES to make it easier for us to make changes or improvements in any TYPE.
3/ I added new TYPE like GROUP and OPERATION.
4/ I created a validation class to validate all the content of and structure of the types in the json file before generating the SQL output.

How to use :
Click on index.php to open the project.
Upload the json file from the uploads folder, we have two json files request-data and my-json (more complicated).
Click on the submit button to see the result on the screen and generate a request.sql file in the same project folder.