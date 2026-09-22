# Hackathon Tournament Manager

A database-driven web application built with PHP and MySQL 
to manage teams and participants for a hackathon tournament. 

## Technologies Used
- PHP
- MySQL
- XAMPP 
- SQL
- HTML/CSS

## Features
- Admin authentication
- Load registered members from a text file
- Create and manage teams
- Add members to teams 
- Edit team names and member details
- Delete teams and members
- Move members between teams
- Input validation

## Setup Instructions
1. Install XAMPP
2. Create a database called codinghackaton in phpMyAdmin
3. Place all files in htdocs/yourfolder/
4. Visit localhost/yourfolder/setupandlogin.php and enter:
        username: ID
        password: Password
    to gain access.
8. Tables are created automatically on first login

## Project Structure
- setupandlogin.php — admin login, creates database tables automatically
- loadmembers.php — loads member registration numbers from members.txt
- initialize.php — set team size and maximum team limits
- mainmenu.php — navigation hub
- createteam.php — create a new team
- addmember.php — add a member to a team
- listteams.php — view all teams and their members
- editteam.php — rename a team
- editmember.php — edit member details
- deleteteam.php — delete a team and its members
- deletemember.php — remove a member from a team
- movemember.php — move a member to a different team
- members.txt — sample registration numbers