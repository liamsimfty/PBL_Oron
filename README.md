# (Project Based Learning) PBL Oron
Is a Group project with combined 4 subject 

With the member of project :
```
liamsimfty
Kefas Hutabarat
Sulthan Isya
Farhan Arya
```

## ORON
What is Oron? Oron is system distributed platform for gaming 

# Installation
1. Install [*PHP*](https://www.php.net/downloads.php), [**Xampp**](https://www.apachefriends.org/download.html), [*Oracle*](https://www.oracle.com/database/technologies/xe-downloads.html) and [*Oracle Install Client*](https://www.oracle.com/id/database/technologies/instant-client/downloads.html) this is video for each installation tutorial [Install PHP](https://www.youtube.com/watch?v=n04w2SzGr_U), [Install Xampp](https://www.youtube.com/watch?v=G2VEf-8nepc),[Install Oracle](https://www.youtube.com/watch?v=fgh2o4hveDk) for oracle install client you just extract to C directory and add the directory to your path environment system variable
2. Git Clone this project ``` https://github.com/liamsimfty/PBL_Oron.git ``` to xampp/htdocs
3. Open xampp turn on the Apache services and open ``` localhost/dashboard/phpinfo.php ``` and make sure OCI8 is there
4. Open connection.php change change the password with your oracle password ``` $conn = oci_connect('system', 'you password', 'localhost/xe'); ```
5. Open ``` localhost/pbl_oron/connection.php ``` if  nothing appear its mean your connection success
