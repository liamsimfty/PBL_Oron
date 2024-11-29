# (Project Based Learning) PBL Oron
Is a Group project with combined 4 subject 

With the member of project :
| Member  | Roles |
| ------------- | ------------- |
| liamsimfty  | BackEnd Developer  |
| Kefas Hutabarat  | FrontEnd Developer  |
| Farhan Arya | UI UX Designer |
| Sulthan Isya | Quality Assurance |

## ORON
Your Gateway to Truly Independent Gaming

What is ORON?
ORON is more than just a digital distribution platform for video games—it's a revolution in how gamers access, own, and enjoy their favorite titles.

Unlike traditional platforms, ORON puts the power back in your hands with features designed for freedom, transparency, and true ownership.

### 1. Independencies
We value your privacy.
At ORON, we don’t store or sell your data. No tracking, no intrusive ads—just a seamless gaming experience where you’re in full control.

### 2. Game Licenses
Your games, your rules.
When you purchase a game on ORON, it’s yours to keep—forever. Even in the unlikely event we go out of business, your ownership remains secure. Download your game, back it up, and enjoy it from alternative sources anytime. We’re committed to giving you true ownership without compromise.

### 3. Accessibility
No DRM, no boundaries.
Play your games offline, share them with friends, or install them on multiple devices. ORON believes in a gaming experience free from restrictions.

### 4. Fair Deals for Creators
Empowering developers, one game at a time.
At ORON, we believe creators deserve their fair share. By choosing our platform, you directly support game developers—whether they’re big corporation or indie pioneers bringing fresh ideas to industry. Your purchase helps sustain their craft, ensuring a bright future for the games and creators you love.



# Installation
1. Install [**PHP**](https://www.php.net/downloads.php), [**Xampp**](https://www.apachefriends.org/download.html), [**Oracle**](https://www.oracle.com/database/technologies/xe-downloads.html) and [**Oracle Install Client**](https://www.oracle.com/id/database/technologies/instant-client/downloads.html) this is video for each installation tutorial [Install PHP](https://www.youtube.com/watch?v=n04w2SzGr_U), [Install Xampp](https://www.youtube.com/watch?v=G2VEf-8nepc),[Install Oracle](https://www.youtube.com/watch?v=fgh2o4hveDk) for oracle install client you just extract to C directory and add the directory to your path environment system variable
2. Git Clone this project ``` git clone https://github.com/liamsimfty/PBL_Oron.git ``` to xampp/htdocs
3. Open xampp turn on the Apache services and open ``` localhost/dashboard/phpinfo.php ``` and make sure OCI8 is there
4. Open connection.php change change the password with your oracle password ``` $conn = oci_connect('system', 'you password', 'localhost/xe'); ```
5. Open ``` localhost/pbl_oron/connection.php ``` if  nothing appear its mean your connection success
