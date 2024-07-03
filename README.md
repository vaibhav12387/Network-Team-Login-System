# Network-Team-Login-System+
Sure! Here is a suggested GitHub description for your Network Team Login System project:

---

## Network Team Login System

The **Network Team Login System** is a secure and efficient multi-role login system designed to manage user access for network teams. This project is built with PHP and MySQL/MariaDB, featuring role-based authentication, user management, and login activity tracking.

### Features

- **Multi-Role Authentication**: Supports different user roles such as admin and user, each with distinct access permissions.
- **Secure Password Storage**: Implements hashed passwords using PHP's `password_hash` function for enhanced security.
- **User Management**: Allows admins to manage users, including adding, updating, and deleting user accounts.
- **Login Activity Tracking**: Logs the last login date and time for each user to monitor account activity.
- **Online Status**: Tracks user online status (Online/Offline) for real-time monitoring.
- **Profile Pictures**: Supports user profile pictures with a default image for new accounts.

### Database Schema

The system includes a `tbl_user` table with the following structure:

- `tbl_user_id` (int): Primary key, auto-incremented.
- `name` (varchar): User's full name.
- `username` (varchar): Unique username for login.
- `email` (varchar): Unique email address.
- `password` (varchar): Hashed password.
- `role` (varchar): User role (e.g., admin, user).
- `last_login_datetime` (datetime): Timestamp of the last login.
- `online_status` (enum): Current online status ('Online', 'Offline').
- `profile_picture` (varchar): Filename of the user's profile picture.

### Installation

1. **Clone the repository**:
   ```sh
   git clone https://github.com/vaibhav12387/network-team-login-system.git
   cd network-team-login-system
   ```

2. **Set up the database**:
   - Import the SQL script (`db_script.sql`) provided in the repository to create the database and tables.
   - Update the database connection settings in the configuration file.

3. **Configure the application**:
   - Update the configuration file with your database credentials and other settings.

4. **Run the application**:
   - Deploy the application on a local server or web server with PHP and MySQL/MariaDB support.

### Usage

- Access the login page to sign in with your username and password.
- Admins can manage users through the admin dashboard.
- Users can update their profiles and view their login activity.

### Contributing

Contributions are welcome! Please fork the repository and create a pull request with your changes. For major changes, open an issue first to discuss what you would like to improve.

### License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
