# Strange-Scouting

## Team 1533 Triple Strange scouting application

**Strange-Scouting** is a web based FRC scouting solution that takes data from a form and inputs it into a MySQL database using PHP. **Strange-Scouting** is set apart from many other scouting applications thanks to its included Slack Integration script. The included `slack-sql.php` file allows for easy integration of your scouting database into your teams Slack Workspace. Slack integration works as a slash (`/`) command, so all you need to do to retrieve data is type the slash command you integrate with - we suggest `/scouting` - and one of the operators listed below.

## Slack Integration Operators

### List `[match/team/note]`

- List all tables in the scouting database, or specify only match, team, or note tables

### Match `<Match Number>`

- Show scouting data by match

### Team `<Team Number>`

- Show scouting data by team

### Notes `<Team Nuber>`

- Show optional notes by team

### Query `<SQL Query>`

- Manually specify a query for the database

### CSV Exporting

- Append "csv" to the end of any command to have it output as CSV formatted text
