This was a small project to create a new page in the WordPress admin menu and display an admin table of posts.  All my edits are in /themes/contacts-db/functions.php (and /themes/contacts-db-child/users.php).  Here are the requirements.

<b>Requirements</b>

<ul>
<li>Create a new page in the WordPress admin menu, named "Reports."</li>
<li>The Reports page will display a table listing all posts of type 'contact' (an existing custom post type.</li>
<li>The table on the Reports page will display data for only eight specific post authors. The IDs of those post authors will be given to you beforehand, and hard coded into the program.</li>
<li>The table will display only the following columns: Contact Name, Contact Creation Date, Contact Author.</li>
<li>See this screenshot for an illustration of the final product.</li>
<li>Above the table, add a row displaying all author names. Next to each author name, in parentheses, display a number indicating how many 'Contacts' that author created. Each name should be a hyperlink, which, when clicked, filters the table by that author name.</li>
<li>Above the table display a search form with two date fields--Start Date and End Date--and a 'Search Button.' Using this form will filter the table by the start and end dates.</li>
</ul>

<b>Approach/Rationale</b>

<ul>
<li>My approach was to use the built-in WordPress <a href="https://codex.wordpress.org/Class_Reference/WP_List_Table">WP_List_Table class</a> to create the table. Building and displaying a table like this seems to be the main purpose of that class.
<li>I did not copy the WP_List_Table class to my child theme, as suggested in the Codex. That is something I probably should have done.</li>
<li>All code was added to my theme's functions.php file, with the exception of a small separate users.php file, which was added to the child theme directory. I realize custom code is typically added to a child theme. But in this case, this was a requirement of the client. Actually, the parent theme in-question was already a custom theme (created and implemented by the client).</li></li>
</ul>
