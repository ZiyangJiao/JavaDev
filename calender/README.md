### Link to calendar site

http://ec2-18-222-230-164.us-east-2.compute.amazonaws.com/~huyueteng/cal/html/

---

### Creative portion

1. **Add tags to event and view events by tags**

   Users can tag an event with a particular category and enable/disable those tags in the calendar view (by select/deselect `checkboxes` on top of calendar).

2. **Sharing one's whole calendar with other users**

   Users can share their calendar with additional users (by adding usernames into the `Share my whole calendar with` list on the bottom of calendar).

3. **Group events**

   Users can create group events that display on multiple users calendars (by adding username into the `Group` in the end of Adding/Editing an event).

5. **Validate forms of users' input info at the frontend** function

   Add form verifying at frontend by JavaScript, if user provide some incompatible info, such as repeat password is not same as the first password, trying to create a group event to share with oneself, then the form won't submit to backend and an alert will display to tell the user.

---

### Login details

Already existing users (including existing news, comments and items in reading list):

- `user1`
  - Username: `user1`
  - Password: `user1`
  - `user1` can see **1)** self-created events, **2)** group events by `user3` and **3)** shared calendar events by `user2`

