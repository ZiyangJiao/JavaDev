### Link to file sharing site

http://ec2-18-222-230-164.us-east-2.compute.amazonaws.com/~huyueteng/file-share/login.php

---

### Creative portion

1. **Uploaded time** of each file is shown on file page (as a column in the table).
   
   We got the file's updated time on system in UNIX Epoch format,  then changed the time zone in php to local time zone, i.e. America/Chicago, and converted the UNIX Epoch time according to this time zone.
   
2. Users can **rename** existing files (click link under rename after logged in).

   On the file rename page, we used `<select>` to generate a drop-down list of existing file names, and excluded the `.` and `..` in system path. Then we filtered the new file name to check if it is validate. If so, we renamed the file and showed to user if renaming succeeded.

3. Users can sort files by filenames in **ascending/descending** order (click links above table).

   We displayed files under user's directory in form of table in ascending order by default. When user clicked on the link to choose ascending/descending order, the choice was passed to the same page as an argument and we used this valuable to sort files name in the table in accordingly.

---

### Login details

Already existing users:

- user1
- user2
- user3



