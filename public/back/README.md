# E-Learning Platform

A Symfony 6.4 web application and Java application designed to deliver a personalized e-learning experience.

## Table of Contents
1. [Group Members](#group-members)
2. [Symfony Part: First Part of the Semester](#symfony-part-first-part-of-the-semester)
   - [Project Setup](#project-setup)
     - [Prerequisites](#prerequisites)
     - [Step-by-Step Setup](#step-by-step-setup)
   - [Branching and Collaboration Workflow](#branching-and-collaboration-workflow)
   - [Verification](#verification)
   - [Symfony Commands](#symfony-commands)
   - [Database Operations](#database-operations)
   - [Entity and Form Creation](#entity-and-form-creation)
   - [Controller and View Operations](#controller-and-view-operations)
   - [How to Use Templates](#how-to-use-templates)
   - [Git Commands](#git-commands)
3. [Java Part: STARTS WEEK 8 --TBC--](#java-part-starts-week-8--tbc--)

## Group Members

- Fawzi Saidi
- Edam Kammoun
- Wiam Fajraoui
- Mohamed Aziz Selmi
- Ahmed Abdelkefi
- Amina Belakhdher

## Symfony Part: First Part of the Semester

### Project Setup

#### Prerequisites
1. Ensure Git is installed:
   ```bash
   git --version
   ```
   If not installed, download Git from [git-scm.com](https://git-scm.com/).

2. Ensure Symfony is installed:
   ```bash
   symfony -v
   ```
   If not installed, follow instructions from the first semester course.

3. Install PHP:
   Download from [php.net](https://www.php.net/).

4. Install XAMPP for MySQL.

#### Step-by-Step Setup
1. **Clone the Repository:**
   ```bash
   git clone https://github.com/FawziSaidi/PIDEV3A-Teamashallah.git
   cd PIDEV3A-Teamashallah
   ```

2. **Configure the Database:**
   In the `.env` file, update the `DATABASE_URL`:
   ```env
   DATABASE_URL="mysql://root:@127.0.0.1:3306/myexam2425?serverVersion=mariadb-10.4.11"
   ```
   Replace `myexam2425` with your actual database name.

3. **Start Symfony Server:**
   ```bash
   symfony serve
   ```

4. **Access the App:**
   Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

### Branching and Collaboration Workflow

0. **Important:** DO NOT PUSH DIRECTLY TO MAIN.

1. **Pull Latest Changes:**
   ```bash
   git remote update
   ```

2. **Create a New Branch:**
   ```bash
   git checkout -b feat/feature-name
   ```
   Use clear names like `feat/auth-login` or `fix/db-connection`.

3. **Stage & Commit Changes:**
   ```bash
   git add .
   git commit -m "Add login feature with JWT authentication"
   ```

4. **Push Your Branch:**
   ```bash
   git push origin feat/feature-name
   ```

### Good Commit Message Examples
- `feat: add user authentication`
- `fix: correct SQL syntax error`
- `refactor: optimize data fetching logic`
- `docs: update README with setup instructions`

### Verification
```bash
symfony -v
php bin/console doctrine:database:validate
```

### Symfony Commands
```bash
composer create-project symfony/skeleton:"6.4.*" project_name
composer require webapp
composer require symfony/maker-bundle --dev
composer require doctrine
symfony server:start
symfony console make:controller ControllerName
```

### Database Operations
```bash
# Configure DB in .env
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate
```

### Entity and Form Creation
```bash
symfony console make:entity
symfony console make:form
```

### Controller and View Operations
```php
return $this->render('view_name.html.twig', ['parameter' => $value]);
$form = $this->createForm(FormType::class, $entity);
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
    // Process data
}
```

### How to Use Templates

1. **Template Branches:**
   - Front Office: `feat/templates/frontoffice`
   - Back Office: `feat/templates/backoffice`

2. **Workflow:**
   ```bash
   git checkout -b feat/templates/frontoffice
   # Make changes to HTML/CSS files
   git add .
   git commit -m "feat: update dashboard layout for front office"
   git push origin feat/templates/frontoffice
   ```

3. **Collaborating:**
   - Always pull latest changes:
     ```bash
     git pull origin feat/templates/frontoffice
     ```
   - Create feature-specific branches from the template branch:
     ```bash
     git checkout -b feat/frontoffice-user-profile
     ```

### Git Commands

Here are common Git commands with explanations for when and how to use them:

```bash
# Check the current branch you are on
# Use this to verify you're not on the 'main' branch before making changes
git branch

# Create and switch to a new branch
# Use this when starting a new feature or fixing a bug
git checkout -b branch_name

# Switch to an existing branch
# Use this to switch between different branches you've already created
git checkout branch_name

# Pull the latest changes from the remote repository for the current branch
# Use this to ensure your code is up-to-date before starting new work
git pull origin branch_name

# Check the status of your working directory
# Use this to see which files have been modified, staged, or are untracked
git status

# View the commit history
# Use this to see the history of commits made on the current branch
git log

# Discard changes in your working directory
# Use this to undo local changes in a specific file (be careful, this cannot be undone)
git checkout -- file_name

# Merge a branch into the current branch
# Use this to combine changes from another branch into your current working branch
git merge branch_name

# Delete a local branch
# Use this to clean up branches that are no longer needed after being merged
git branch -d branch_name

# Delete a remote branch
# Use this when you want to remove a branch from the remote repository
git push origin --delete branch_name
```

## Java Part: STARTS WEEK 8 --TBC--
Further details will be provided in Week 8. Stay tuned!
