# E-Learning Platform

A Symfony 6.4 web application and Java application designed to deliver a personalized e-learning experience.
## Group Members

- Fawzi Saidi
- Edam Kammoun
- Wiam Fajraoui
- Mohamed Aziz Selmi
- Ahmed Abdelkefi
- Amina Belakhdher
## Project Setup

### Prerequisites
1. Ensure Git is installed on your machine:
   ```bash
   git --version
   ```
   If not installed, download and install Git from [git-scm.com](https://git-scm.com/).

2. Ensure Symfony is installed:
   ```bash
   symfony -v
   ```
   If not installed, follow the intructions from the first semester course.

3. Install PHP (if not already installed):
   - PHP: [php.net](https://www.php.net/manual/en/install.php)

4. Install XAMPP for MySQL.

### Clone the Repository

1. Open a terminal and navigate to the directory where you want to clone the project.
   ```bash
   cd /path/to/your/directory
   ```

2. Clone the repository:
   ```bash
   git clone https://github.com/FawziSaidi/PIDEV3A-Teamashallah.git
   ```

3. Navigate into the project directory:
   ```bash
   cd path/to/repo/inside/your/directory
   ```

### Set Up the Local Environment

1. Start the Symfony server:
   ```bash
   symfony serve
   ```

2. Open the app in your browser:
   [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Branching and Collaboration Workflow

#### 0. Very important ! DO NOT PUSH ON MAIN
   - Verify you are not on the "main" branch before starting to do any modification.
   - Before making any modifications, make sure you have the latest version of the project.
    ```bash
    git remote update
    ```
#### 1. Create a New Branch
   - IMPORTANT /!\ : Please always create a new branch for each feature or fix :
     ```bash
     git checkout -b feat/name_of_the_feat
     ```
     Please avoid names like Fawzi_fix, Fawzi_new_feature_whatever, names should be in the format : feat/x for Features, fix/x for hotfixes or bugfixes..

#### 2. Stage and Commit Changes
   - Stage changes:
     ```bash
     git add .
     ```
     Note : this adds ALL the changes, otherwise git add filename1 filename2
   - Commit changes:
     ```bash
     git commit -m "Description of the changes"
     ```
     Please make commits explicit

#### 3. Push Changes to Remote Repository
   - Push your branch:
     ```bash
     git push origin feat/name_of_the_feat
     ```
     feat/name_of_the_feat should be replaced by the actual branch name that is visible on the lower left in the VSCode.


### Verification

1. Check Symfony version:
   ```bash
   symfony -v
   ```

2. Verify database connection:
   ```bash
   php bin/console doctrine:database:validate
   ```
