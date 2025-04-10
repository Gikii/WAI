# WAI
Creating web applications, PG 2023/2024

Web Application in PHP & MongoDB
A university project for the Web Application Development course (Informatics, 1st semester, 2023/2024).

## Features

- **Image Upload**  
  Upload JPG/PNG images (max 1MB). Invalid format/size triggers appropriate messages.

- **Watermark & Thumbnail**  
  Adds a custom text watermark and generates a 200x125px thumbnail using PHP GD.

- **Image Gallery**  
  Displays thumbnails with pagination. Clicking a thumbnail shows the watermarked full-size image.

- **Database Integration**  
  Stores image metadata (title, author) in MongoDB.

- **User System**  
  Registration & login with password hashing. Unique logins enforced.

- **Session Mechanism**  
  Users can "remember" selected images (like a cart). Saved selections persist via session and can be reviewed/edited.

- **User Roles**  
  Logged-in users auto-fill the author field and can mark uploads as "public" or "private".

- PHP (with GD library)
- MongoDB
- HTML/CSS
