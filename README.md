# Strange-Scouting

### THIS IS THE ARCHIVED PROOF OF CONCEPT, PLEASE SEE: https://github.com/triplestrange/StrangeScout (currently private pending v1.0 release).

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

### Average `<Team Number>`

- Average power cube data for a specific team over all of their matches

### Query `<SQL Query>`

- Manually specify a query for the database

## HTML Table and CSV Files

The included `html-table.php` file will be linked to by the returned text of the slack integration bot. It is capable of generating HTML tables on the fly to easily display data. CSV files will also be generated and stored in the `csv` folder, with a download link included in the slack message. The owner of the `csv` folder must be changed to your web server user server side before the csv files can be created.
