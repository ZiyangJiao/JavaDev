### Link to news site

http://ec2-18-222-230-164.us-east-2.compute.amazonaws.com/~huyueteng/news/

---

### Creative portion

1. **Add to reading list** function and **show if already in reading list** function

   We created a separate table in MySQL to store user's reading list. On the homepage (as the link above), if user logged in, the page will query with MySQL to show if each news is already in user's reading list or not. If so, the page will show `✓` symbol.  If not, a `+ Add` will show and allow user to add into reading list, which will insert a new entry into the table of reading list.

2. **View next news** function when viewing each news

   When user is view the details/comments of each news. The page will query with MySQL to check if there exists a **next news**. If so, the page will show a button, which is convenient for user to click to navigate to next news directly. If not, the page will show the user that this is the last news.

3. **Manage reading list** function

   We created a page for user to manage all items in reading list. After logging in, `View/Manage my reading list` button will show up on the right. On the page, the user has the option to view the news which were added earlier. The user also has the option to delete a certain news from the reading list on the reading list page. When doing so, the corresponding entry will also be deleted from the table of reading list.

4. **Different colors for different levels of operation** function

   To help user distinguish different levels of operation (ie delete, edit and view), we have implemented different colors on buttons/links across the whole news site. `Red` is to used to alert user that this is a `delete` function. `Orange` is for `Edit` function and `blue/green` for `view` function. This is extremely helpful to provide intuitive optical information to users.

5. **Validate forms of users' input info at the frontend** function

   Add form verifying at frontend by JavaScript, if user provide some incompatible info, like repeat password is not same as the first password, then the form won't submit to backend.
   
---

### Login details

Already existing users (including existing news, comments and items in reading list):

- user1
  - Username: user1
  - Password: user1


