# Email Sending Web Application with PHPMailer

A user-friendly web application that allows users to send emails securely using PHPMailer. This project is designed to demonstrate how to integrate PHPMailer into a PHP-based web application for sending emails.


---

## Features

- **Easy-to-Use Form:** Simple and intuitive interface for submitting emails.
- **Secure Email Sending:** Uses PHPMailer for secure and reliable email delivery.
- **Responsive Design:** Works seamlessly on all devices (desktop, tablet, mobile).
- **Real-Time Feedback:** Users receive instant feedback on email submission status.
- **Customizable:** Easily configure SMTP settings for different email providers.

---

## Technologies Used

- **PHP:** Backend logic and email handling.
- **PHPMailer:** Email sending library.
- **HTML/CSS:** Frontend design.
- **Bootstrap:** Responsive design framework.
- **JavaScript:** Client-side validation and interactivity.

---

## How It Works

1. **User Submission:** The user fills out the form with their email address, subject, and message.
2. **Form Submission:** The form data is sent to the server using the POST method.
3. **PHPMailer Processing:** The server processes the form data, validates it, and uses PHPMailer to send the email.
4. **Feedback to User:** The user is notified whether the email was sent successfully or if an error occurred.

---

## Installation

To set up this project locally, follow these steps:

### Prerequisites

- A web server with PHP support (e.g., Apache, Nginx).
- Composer (for installing PHPMailer).

### Steps

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/your-repo-name.git
   cd your-repo-name
   ```
2. **Install Dependencies:** Install PHPMailer using Composer:
   ```bash
   composer require phpmailer/phpmailer
   ```
3. **Configure PHPMailer:** Open the `send_email.php` file and update the SMTP settings with your email provider's details (e.g., Gmail, Outlook).
   ```php
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
   $mail->SMTPAuth = true;
   $mail->Username = 'your-email@gmail.com'; // Replace with your email
   $mail->Password = 'your-email-password'; // Replace with your email password
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
   $mail->Port = 587;
   ```
4. **Run the Application:**
   - Start your local web server and navigate to the project directory.
   - Open the `index.html` file in your browser to access the form.

---

## Usage

1. Open the form in your browser.
2. Fill in the required fields (email, subject, message).
3. Click the "Send" button.
4. You will receive a notification indicating whether the email was sent successfully or if an error occurred.

---

## Contributing

Contributions are welcome! If you'd like to contribute, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes.
4. Push your branch and submit a pull request.

---

## Acknowledgments

- **PHPMailer** for providing a robust email-sending library.
- **Bootstrap** for the responsive design framework.

---

## Contact

If you have any questions or feedback, feel free to reach out:

- **Your Name**
- **Email:** [your.email@example.com](mailto\:oguzhan.mrtarslan@gmail.com)
- **GitHub:** [yourusername](https://github.com/komplatox)

