<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>iViettech - Hệ thống Quản lý Học viên</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-color: #2563eb;
      --secondary-color: #0ea5e9;
      --accent-color: #f59e0b;
      --success-color: #10b981;
      --danger-color: #ef4444;
      --dark-color: #1f2937;
      --light-color: #f8fafc;
      --text-primary: #1f2937;
      --text-secondary: #6b7280;
      --border-color: #e5e7eb;
      --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    body {
      font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: var(--text-primary);
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
    }

    /* Particles Background */
    .particles-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    }

    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      pointer-events: none;
      animation: float 8s infinite linear;
    }

    .particle:nth-child(odd) {
      animation-direction: reverse;
    }

    /* Header */
    .header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      z-index: 1000;
      transition: all 0.3s ease;
    }

    .header.scrolled {
      background: rgba(255, 255, 255, 0.98);
      box-shadow: var(--shadow);
    }

    .nav-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
      font-size: 1.75rem;
      font-weight: 800;
      color: var(--primary-color);
      transition: transform 0.3s ease;
    }

    .logo:hover {
      transform: scale(1.05);
    }

    .logo-icon {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
    }

    .nav-menu {
      display: flex;
      list-style: none;
      gap: 2rem;
      align-items: center;
    }

    .nav-link {
      text-decoration: none;
      color: var(--text-primary);
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-link:hover {
      color: var(--primary-color);
      background: rgba(37, 99, 235, 0.1);
    }

    .login-btn {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: var(--shadow);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    /* Hero Section */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      position: relative;
      padding: 0 2rem;
    }

    .hero-content {
      max-width: 1000px;
      z-index: 2;
      animation: fadeInUp 1s ease;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-size: 0.9rem;
      margin-bottom: 2rem;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero h1 {
      font-size: 4rem;
      font-weight: 900;
      margin-bottom: 1.5rem;
      line-height: 1.1;
      background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-subtitle {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: #f0f9ff;
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 3rem;
      opacity: 0.9;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.7;
    }

    .cta-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .cta-button {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 1rem 2rem;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: var(--shadow);
    }

    .cta-primary {
      background: linear-gradient(135deg, #f59e0b, #f97316);
      color: white;
    }

    .cta-secondary {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .cta-button:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-lg);
    }

    /* Login Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(5px);
    }

    .modal-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      border-radius: 20px;
      padding: 3rem;
      width: 90%;
      max-width: 500px;
      box-shadow: var(--shadow-lg);
      animation: modalSlideIn 0.3s ease;
    }

    .modal-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .modal-header h2 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .modal-header p {
      color: var(--text-secondary);
    }

    .close {
      position: absolute;
      right: 1rem;
      top: 1rem;
      color: var(--text-secondary);
      font-size: 1.5rem;
      cursor: pointer;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .close:hover {
      background: #f3f4f6;
      color: var(--text-primary);
    }

    .role-selection {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .role-card {
      background: #f8fafc;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      padding: 1.5rem;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
    }

    .role-card:hover {
      border-color: var(--primary-color);
      background: rgba(37, 99, 235, 0.05);
      transform: translateY(-2px);
    }

    .role-card.active {
      border-color: var(--primary-color);
      background: rgba(37, 99, 235, 0.1);
    }

    .role-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto 1rem;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
    }

    .student-card .role-icon {
      background: linear-gradient(135deg, var(--secondary-color), #06b6d4);
    }

    .teacher-card .role-icon {
      background: linear-gradient(135deg, var(--success-color), #059669);
    }

    .admin-card .role-icon {
      background: linear-gradient(135deg, var(--danger-color), #dc2626);
    }

    .role-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .role-desc {
      font-size: 0.9rem;
      color: var(--text-secondary);
    }

    .login-form {
      display: none;
    }

    .login-form.show {
      display: block;
      animation: fadeIn 0.3s ease;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    .form-group input {
      width: 100%;
      padding: 1rem;
      border: 2px solid var(--border-color);
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: white;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .login-submit {
      width: 100%;
      padding: 1rem;
      border: none;
      border-radius: 12px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1rem;
      color: white;
    }

    .login-submit.student {
      background: linear-gradient(135deg, var(--secondary-color), #06b6d4);
    }

    .login-submit.teacher {
      background: linear-gradient(135deg, var(--success-color), #059669);
    }

    .login-submit.admin {
      background: linear-gradient(135deg, var(--danger-color), #dc2626);
    }

    .login-submit:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .back-btn {
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
      margin-bottom: 1rem;
    }

    .back-btn:hover {
      background: #f3f4f6;
      color: var(--text-primary);
    }

    /* Stats Section */
    .stats {
      padding: 6rem 2rem;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stats-grid {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }

    .stat-item {
      text-align: center;
      color: white;
    }

    .stat-number {
      font-size: 3rem;
      font-weight: 900;
      margin-bottom: 0.5rem;
      background: linear-gradient(135deg, #ffffff, #f0f9ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .stat-label {
      font-size: 1.1rem;
      opacity: 0.9;
      font-weight: 500;
    }

    /* Features Section */
    .features {
      padding: 8rem 2rem;
      background: white;
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .section-header {
      text-align: center;
      margin-bottom: 5rem;
    }

    .section-badge {
      display: inline-block;
      background: rgba(37, 99, 235, 0.1);
      color: var(--primary-color);
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .section-header h2 {
      font-size: 3rem;
      font-weight: 900;
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .section-header p {
      font-size: 1.2rem;
      color: var(--text-secondary);
      max-width: 600px;
      margin: 0 auto;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
    }

    .feature-card {
      background: white;
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 2.5rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .feature-card:hover::before {
      transform: scaleX(1);
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-lg);
      border-color: rgba(37, 99, 235, 0.2);
    }

    .feature-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
    }

    .feature-title {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .feature-desc {
      color: var(--text-secondary);
      line-height: 1.7;
    }

    /* Course Section */
    .courses {
      padding: 8rem 2rem;
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .courses-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-top: 3rem;
    }

    .course-card {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .course-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(135deg, var(--accent-color), #f97316);
    }

    .course-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-lg);
    }

    .course-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, var(--accent-color), #f97316);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .course-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .course-desc {
      color: var(--text-secondary);
      line-height: 1.7;
      margin-bottom: 1.5rem;
    }

    .course-features {
      list-style: none;
      margin-bottom: 1.5rem;
    }

    .course-features li {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }

    .course-features i {
      color: var(--success-color);
      font-size: 0.8rem;
    }

    .course-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      text-decoration: none;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .course-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    /* Testimonials Section */
    .testimonials {
      padding: 8rem 2rem;
      background: white;
    }

    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
      margin-top: 3rem;
    }

    .testimonial-card {
      background: white;
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      position: relative;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-lg);
      border-color: rgba(37, 99, 235, 0.2);
    }

    .testimonial-quote {
      font-size: 1rem;
      line-height: 1.7;
      color: var(--text-secondary);
      margin-bottom: 1.5rem;
      font-style: italic;
      position: relative;
    }

    .testimonial-quote::before {
      content: '"';
      font-size: 3rem;
      color: var(--primary-color);
      position: absolute;
      top: -1rem;
      left: -0.5rem;
      font-family: Georgia, serif;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .author-avatar {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
    }

    .author-info h4 {
      font-weight: 600;
      color: var(--text-primary);
    }

    .author-info p {
      font-size: 0.9rem;
      color: var(--text-secondary);
    }

    /* Notice Board */
    .notice-board {
      padding: 8rem 2rem;
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .notice-container {
      background: white;
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 3rem;
      box-shadow: var(--shadow);
    }

    .notice-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .notice-header h3 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-primary);
    }

    .notice-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, var(--accent-color), #f97316);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
    }

    .notice-scroll {
      max-height: 500px;
      overflow-y: auto;
    }

    .notice-scroll::-webkit-scrollbar {
      width: 6px;
    }

    .notice-scroll::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 10px;
    }

    .notice-scroll::-webkit-scrollbar-thumb {
      background: var(--primary-color);
      border-radius: 10px;
    }

    .notice-item {
      display: block;
      padding: 1.5rem;
      margin-bottom: 1rem;
      background: #f8fafc;
      border: 1px solid var(--border-color);
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .notice-item::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }

    .notice-item:hover::before {
      transform: scaleY(1);
    }

    .notice-item:hover {
      transform: translateX(10px);
      background: white;
      box-shadow: var(--shadow);
      border-color: rgba(37, 99, 235, 0.2);
    }

    .notice-title {
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
    }

    .notice-date {
      font-size: 0.9rem;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .notice-priority {
      display: inline-block;
      padding: 0.25rem 0.5rem;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 600;
      margin-left: 0.5rem;
    }

    .priority-high {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger-color);
    }

    .priority-new {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success-color);
    }

    /* Footer */
    .footer {
      background: var(--dark-color);
      color: white;
      padding: 4rem 2rem 2rem;
    }

    .footer-content {
      max-width: 1400px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 3rem;
      margin-bottom: 3rem;
    }

    .footer-section h3 {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: white;
    }

    .footer-section p,
    .footer-section a {
      color: #9ca3af;
      line-height: 1.7;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-section a:hover {
      color: white;
    }

    .footer-links {
      list-style: none;
    }

    .footer-links li {
      margin-bottom: 0.5rem;
    }

    .contact-info {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .footer-bottom {
      border-top: 1px solid #374151;
      padding-top: 2rem;
      text-align: center;
      color: #9ca3af;
    }

    .social-links {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }

    .social-link {
      width: 40px;
      height: 40px;
      background: #374151;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #9ca3af;
      transition: all 0.3s ease;
    }

    .social-link:hover {
      background: var(--primary-color);
      color: white;
      transform: translateY(-2px);
    }

    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.1;
      }

      50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 0.3;
      }
    }

    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: translate(-50%, -60%);
      }

      to {
        opacity: 1;
        transform: translate(-50%, -50%);
      }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .nav-menu {
        display: none;
      }

      .hero h1 {
        font-size: 2.5rem;
      }

      .hero-subtitle {
        font-size: 1.2rem;
      }

      .cta-buttons {
        flex-direction: column;
        align-items: center;
      }

      .features-grid,
      .courses-grid,
      .testimonials-grid {
        grid-template-columns: 1fr;
      }

      .section-header h2 {
        font-size: 2rem;
      }

      .modal-content {
        width: 95%;
        padding: 2rem;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
      }
    }
  </style>
</head>

<body>
  <!-- Particles Background -->
  <div class="particles-bg" id="particles"></div>

  <!-- Header -->
  <header class="header" id="header">
    <div class="nav-container">
      <a href="#" class="logo">
        <div class="logo-icon">
          <i class="fas fa-laptop-code"></i>
        </div>
        iViettech
      </a>
      <nav>
        <ul class="nav-menu">
          <li><a href="#home" class="nav-link">Trang chủ</a></li>
          <li><a href="#courses" class="nav-link">Khóa học</a></li>
          <li><a href="#features" class="nav-link">Ưu điểm</a></li>
          <li><a href="#testimonials" class="nav-link">Học viên</a></li>
          <li><a href="#notices" class="nav-link">Thông báo</a></li>
          <li><a href="#contact" class="nav-link">Liên hệ</a></li>
        </ul>
        <button class="login-btn" onclick="openModal()">
          <i class="fas fa-sign-in-alt"></i>
          Đăng nhập
        </button>
      </nav>
    </div>
  </header>

  <!-- Login Modal -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>

      <div class="modal-header">
        <h2>Đăng nhập hệ thống</h2>
        <p>Chọn vai trò để truy cập hệ thống quản lý</p>
      </div>

      <div id="roleSelection" class="role-selection">
        <div class="role-card student-card" onclick="selectRole('student')">
          <div class="role-icon"><i class="fas fa-user-graduate"></i></div>
          <div class="role-title">Học viên</div>
          <div class="role-desc">Xem bài giảng, nộp bài tập, kiểm tra điểm số</div>
        </div>

        <div class="role-card teacher-card" onclick="selectRole('teacher')">
          <div class="role-icon"><i class="fas fa-chalkboard-teacher"></i></div>
          <div class="role-title">Giảng viên</div>
          <div class="role-desc">Quản lý lớp học, chấm điểm, theo dõi tiến độ</div>
        </div>

        <div class="role-card admin-card" onclick="selectRole('admin')">
          <div class="role-icon"><i class="fas fa-user-shield"></i></div>
          <div class="role-title">Quản trị viên</div>
          <div class="role-desc">Quản lý toàn bộ hệ thống và người dùng</div>
        </div>
      </div>

      <form id="loginForm" class="login-form">
        <button type="button" class="back-btn" onclick="backToRoleSelection()">
          <i class="fas fa-arrow-left"></i> Quay lại
        </button>

        <div class="form-group">
          <label for="username">Tên đăng nhập</label>
          <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập..." required>
        </div>

        <div class="form-group">
          <label for="password">Mật khẩu</label>
          <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." required>
        </div>

        <button type="submit" id="loginSubmit" class="login-submit">
          <i class="fas fa-sign-in-alt"></i> Đăng nhập
        </button>
      </form>
    </div>
  </div>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="hero-content">
      <div class="hero-badge">
        <i class="fas fa-award"></i>
        <span>Trung tâm đào tạo CNTT hàng đầu Đà Nẵng</span>
      </div>
      <h1>iViettech</h1>
      <div class="hero-subtitle">Trung tâm Đào tạo Công nghệ Thông tin</div>
      <p>Chúng tôi đào tạo lập trình viên chuyên nghiệp với kiến thức thực tế, đội ngũ giảng viên giàu kinh nghiệm và chương trình học cập nhật. Cam kết hỗ trợ tìm việc làm sau khi tốt nghiệp với tỷ lệ thành công cao.</p>
      <div class="cta-buttons">
        <a href="#courses" class="cta-button cta-primary">
          <i class="fas fa-graduation-cap"></i> Xem khóa học
        </a>
        <a href="#" class="cta-button cta-secondary" onclick="openModal()">
          <i class="fas fa-sign-in-alt"></i> Đăng nhập học viên
        </a>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats">
    <div class="stats-grid">
      <div class="stat-item">
        <div class="stat-number" data-target="1000">0</div>
        <div class="stat-label">Học viên đã tốt nghiệp</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-target="15">0</div>
        <div class="stat-label">Năm kinh nghiệm</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-target="95">0</div>
        <div class="stat-label">% Có việc làm sau tốt nghiệp</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-target="50">0</div>
        <div class="stat-label">Doanh nghiệp đối tác</div>
      </div>
    </div>
  </section>

  <!-- Courses Section -->
  <section class="courses" id="courses">
    <div class="container">
      <div class="section-header">
        <div class="section-badge">Khóa học nổi bật</div>
        <h2>Chương trình đào tạo</h2>
        <p>Các khóa học được thiết kế phù hợp với nhu cầu thực tế của doanh nghiệp</p>
      </div>
      <div class="courses-grid">
        <div class="course-card">
          <div class="course-icon"><i class="fab fa-html5"></i></div>
          <h3 class="course-title">Web Development</h3>
          <p class="course-desc">Học lập trình web từ cơ bản đến nâng cao với HTML, CSS, JavaScript, PHP, MySQL</p>
          <ul class="course-features">
            <li><i class="fas fa-check-circle"></i> HTML5, CSS3, JavaScript ES6+</li>
            <li><i class="fas fa-check-circle"></i> PHP & MySQL</li>
            <li><i class="fas fa-check-circle"></i> Framework Laravel, ReactJS</li>
            <li><i class="fas fa-check-circle"></i> Thực hành dự án thực tế</li>
            <li><i class="fas fa-check-circle"></i> Hỗ trợ tìm việc làm</li>
          </ul>
          <a href="#" class="course-btn">
            <i class="fas fa-info-circle"></i> Xem chi tiết
          </a>
        </div>

        <div class="course-card">
          <div class="course-icon"><i class="fab fa-android"></i></div>
          <h3 class="course-title">Mobile App Development</h3>
          <p class="course-desc">Phát triển ứng dụng di động đa nền tảng với React Native và Flutter</p>
          <ul class="course-features">
            <li><i class="fas fa-check-circle"></i> React Native cơ bản đến nâng cao</li>
            <li><i class="fas fa-check-circle"></i> Flutter & Dart</li>
            <li><i class="fas fa-check-circle"></i> API Integration</li>
            <li><i class="fas fa-check-circle"></i> Deploy lên App Store/Play Store</li>
            <li><i class="fas fa-check-circle"></i> Dự án thực tế</li>
          </ul>
          <a href="#" class="course-btn">
            <i class="fas fa-info-circle"></i> Xem chi tiết
          </a>
        </div>

        <div class="course-card">
          <div class="course-icon"><i class="fas fa-database"></i></div>
          <h3 class="course-title">Data Science & AI</h3>
          <p class="course-desc">Khóa học về khoa học dữ liệu và trí tuệ nhân tạo với Python</p>
          <ul class="course-features">
            <li><i class="fas fa-check-circle"></i> Python cơ bản đến nâng cao</li>
            <li><i class="fas fa-check-circle"></i> Machine Learning</li>
            <li><i class="fas fa-check-circle"></i> Deep Learning với TensorFlow</li>
            <li><i class="fas fa-check-circle"></i> Data Visualization</li>
            <li><i class="fas fa-check-circle"></i> Dự án AI thực tế</li>
          </ul>
          <a href="#" class="course-btn">
            <i class="fas fa-info-circle"></i> Xem chi tiết
          </a>
        </div>

        <div class="course-card">
          <div class="course-icon"><i class="fab fa-java"></i></div>
          <h3 class="course-title">Java Programming</h3>
          <p class="course-desc">Lập trình Java từ cơ bản đến nâng cao, Spring Framework</p>
          <ul class="course-features">
            <li><i class="fas fa-check-circle"></i> Java Core & OOP</li>
            <li><i class="fas fa-check-circle"></i> Spring Boot Framework</li>
            <li><i class="fas fa-check-circle"></i> Database với JPA/Hibernate</li>
            <li><i class="fas fa-check-circle"></i> RESTful API</li>
            <li><i class="fas fa-check-circle"></i> Microservices</li>
          </ul>
          <a href="#" class="course-btn">
            <i class="fas fa-info-circle"></i> Xem chi tiết
          </a>
        </div>

        <div class="course-card">
          <div class="course-icon"><i class="fab fa-js-square"></i></div>
          <h3 class="course-title">Full-stack JavaScript</h3>
          <p class="course-desc">NodeJS, ReactJS, MongoDB - Trở thành Full-stack Developer</p>
          <ul class="course-features">
            <li><i class="fas fa-check-circle"></i> NodeJS & Express.js</li>
            <li><i class="fas fa-check-circle"></i> ReactJS & Redux</li>
            <li><i class="fas fa-check-circle"></i> MongoDB & Mongoose</li>
            <li><i class="fas fa-check-circle"></i> RESTful API & GraphQL</li>
            <li><i class="fas fa-check-circle"></i> Deployment & DevOps</li>
          </ul>
          <a href="#" class="course-btn">
            <i class="fas fa-info-circle"></i> Xem chi tiết
          </a>
        </div>

        <div class="course-card">
          <div class="course-icon"><i class="fas fa-shield-alt"></i></div>
          <h3 class="course-title">Cyber Security</h3>
          <p class="course-desc">An toàn thông tin và bảo mật hệ thống</p>
          <ul class="course-features">
            <li><i class="fas fa-check-circle"></i> Network Security</li>
            <li><i class="fas fa-check-circle"></i> Ethical Hacking</li>
            <li><i class="fas fa-check-circle"></i> Penetration Testing</li>
            <li><i class="fas fa-check-circle"></i> Security Tools & Frameworks</li>
            <li><i class="fas fa-check-circle"></i> Incident Response</li>
          </ul>
          <a href="#" class="course-btn">
            <i class="fas fa-info-circle"></i> Xem chi tiết
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" id="features">
    <div class="container">
      <div class="section-header">
        <div class="section-badge">Ưu điểm nổi bật</div>
        <h2>Tại sao chọn iViettech?</h2>
        <p>Những lợi thế vượt trội giúp bạn thành công trong sự nghiệp CNTT</p>
      </div>
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-users"></i></div>
          <h3 class="feature-title">Giảng viên giàu kinh nghiệm</h3>
          <p class="feature-desc">Đội ngũ giảng viên có nhiều năm kinh nghiệm thực tế trong các dự án lớn, truyền đạt kiến thức một cách dễ hiểu và thực tế.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-laptop-code"></i></div>
          <h3 class="feature-title">Thực hành dự án thực tế</h3>
          <p class="feature-desc">Học viên được thực hiện các dự án thực tế trong quá trình học, giúp tích lũy kinh nghiệm và portfolio ấn tượng.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-handshake"></i></div>
          <h3 class="feature-title">Hỗ trợ tìm việc làm</h3>
          <p class="feature-desc">Cam kết hỗ trợ tìm việc làm sau khi tốt nghiệp với mạng lưới doanh nghiệp đối tác rộng khắp.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-clock"></i></div>
          <h3 class="feature-title">Lịch học linh hoạt</h3>
          <p class="feature-desc">Đa dạng khung giờ học phù hợp với học viên đi làm: sáng, chiều, tối và cuối tuần.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-tools"></i></div>
          <h3 class="feature-title">Trang thiết bị hiện đại</h3>
          <p class="feature-desc">Phòng học được trang bị máy tính cấu hình cao, màn hình lớn và các công cụ hỗ trợ học tập tốt nhất.</p>
        </div>

        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-certificate"></i></div>
          <h3 class="feature-title">Chứng chỉ uy tín</h3>
          <p class="feature-desc">Cấp chứng chỉ hoàn thành khóa học được các doanh nghiệp trong ngành công nhận và đánh giá cao.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials" id="testimonials">
    <div class="container">
      <div class="section-header">
        <div class="section-badge">Học viên nói gì về chúng tôi</div>
        <h2>Câu chuyện thành công</h2>
        <p>Những chia sẻ chân thực từ học viên đã thành công sau khi tốt nghiệp tại iViettech</p>
      </div>
      <div class="testimonials-grid">
        <div class="testimonial-card">
          <div class="testimonial-quote">
            Trung tâm iViettech là lựa chọn hợp lý cho các bạn muốn theo đuổi ngành CNTT. Trung tâm có đội ngũ giảng viên có chuyên môn, có kinh nghiệm và rất nhiệt tình trong quá trình giảng dạy. Ngoài ra, trung tâm còn có chế độ hỗ trợ tìm việc làm sau khi tốt nghiệp rất tốt.
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">HV1</div>
            <div class="author-info">
              <h4>Học viên khóa Web</h4>
              <p>Hiện đang làm việc tại công ty IT</p>
            </div>
          </div>
        </div>

        <div class="testimonial-card">
          <div class="testimonial-quote">
            Đến với trung tâm iViettech, em đã được các thầy cô truyền đạt những kiến thức cần thiết cho ngành học cũng như công việc của mình sau này, được chia sẻ những kinh nghiệm khi đi làm; được tư vấn và hỗ trợ tận tình trong quá trình làm CV và tìm việc làm. Nhờ sự giúp đỡ và hỗ trợ tận tình nên em đã có việc làm sau khi hoàn thành khóa học.
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">HV2</div>
            <div class="author-info">
              <h4>Học viên khóa Java</h4>
              <p>Software Developer</p>
            </div>
          </div>
        </div>

        <div class="testimonial-card">
          <div class="testimonial-quote">
            Trung tâm iViettech là lựa chọn hợp lý cho các bạn đang muốn theo đuổi ngành CNTT. Trung tâm có đội ngũ giảng viên chuyên môn cao, nhiệt tình chỉ dạy cho học viên. Kiến thức giảng dạy bám sát thực tế. Bên cạnh việc giảng dạy thì trung tâm cũng có những buổi ngoại khóa tham quan các công ty phần mềm, giúp học viên được tham quan môi trường làm việc.
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">HV3</div>
            <div class="author-info">
              <h4>Học viên khóa Mobile</h4>
              <p>Mobile App Developer</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Notice Board -->
  <section class="notice-board" id="notices">
    <div class="container">
      <div class="notice-container">
        <div class="notice-header">
          <div class="notice-icon">
            <i class="fas fa-bullhorn"></i>
          </div>
          <h3>Bảng thông báo</h3>
        </div>
        <div class="notice-scroll">
          <a href="#" class="notice-item">
            <div class="notice-title">🤝 Agribank Nam Đà Nẵng hợp tác cùng iViettech đẩy mạnh chuyển đổi số bằng AI</div>
            <div class="notice-date">
              <i class="fas fa-calendar"></i> 22/07/2025
              <span class="notice-priority priority-new">MỚI NHẤT</span>
            </div>
          </a>
          <a href="#" class="notice-item">
            <div class="notice-title">🎓 Khai giảng lớp Web Development batch mới - Còn 5 suất cuối</div>
            <div class="notice-date">
              <i class="fas fa-calendar"></i> 20/07/2025
              <span class="notice-priority priority-high">HOT</span>
            </div>
          </a>
          <a href="#" class="notice-item">
            <div class="notice-title">💼 Workshop "Cách viết CV và phỏng vấn xin việc thành công"</div>
            <div class="notice-date">
              <i class="fas fa-calendar"></i> 18/07/2025
            </div>
          </a>
          <a href="#" class="notice-item">
            <div class="notice-title">🏆 Chúc mừng 15 học viên khóa Java đã tìm được việc làm</div>
            <div class="notice-date">
              <i class="fas fa-calendar"></i> 15/07/2025
            </div>
          </a>
          <a href="#" class="notice-item">
            <div class="notice-title">📱 Mở đăng ký khóa học React Native - Phát triển ứng dụng di động</div>
            <div class="notice-date">
              <i class="fas fa-calendar"></i> 12/07/2025
            </div>
          </a>
          <a href="#" class="notice-item">
            <div class="notice-title">🎯 Hội thảo "Xu hướng công nghệ 2025 và cơ hội việc làm"</div>
            <div class="notice-date">
              <i class="fas fa-calendar"></i> 10/07/2025
            </div>
          </a>
          <a href="#" class="notice-item">
            <div class="notice-title">📊 Khảo sát mức độ hài lòng của học viên - Kết quả 98% hài lòng</div>
            <div class="notice-date">
              <i class="fas fa-calendar"></i> 08/07/2025
            </div>
          </a>
          <a href="#" class="notice-item">
            <div class="notice-title">🔥 Flash Sale - Giảm 30% học phí cho 20 học viên đầu tiên đăng ký</div>
            <div class="notice-date">
              <i class="fas fa-calendar"></i> 05/07/2025
              <span class="notice-priority priority-high">KHUYẾN MÃI</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer" id="contact">
    <div class="footer-content">
      <div class="footer-section">
        <h3>Trung tâm iViettech</h3>
        <p>Trung tâm đào tạo lập trình chuyên nghiệp tại Đà Nẵng. Chúng tôi cam kết mang đến chất lượng giảng dạy tốt nhất và hỗ trợ học viên tìm được việc làm ưng ý sau khi tốt nghiệp.</p>
        <div class="social-links">
          <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
          <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
          <a href="#" class="social-link"><i class="fab fa-zalo"></i></a>
          <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>

      <div class="footer-section">
        <h3>Khóa học</h3>
        <ul class="footer-links">
          <li><a href="#">Lập trình Web</a></li>
          <li><a href="#">Lập trình Mobile</a></li>
          <li><a href="#">Data Science & AI</a></li>
          <li><a href="#">Java Programming</a></li>
          <li><a href="#">Full-stack JavaScript</a></li>
          <li><a href="#">Cyber Security</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>Hỗ trợ</h3>
        <ul class="footer-links">
          <li><a href="#">Tư vấn khóa học</a></li>
          <li><a href="#">Hỗ trợ kỹ thuật</a></li>
          <li><a href="#">Tìm việc làm</a></li>
          <li><a href="#">Câu hỏi thường gặp</a></li>
          <li><a href="#">Chính sách bảo mật</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>Liên hệ</h3>
        <div class="contact-info">
          <i class="fas fa-map-marker-alt"></i>
          <span>92 Quang Trung, Hải Châu, Đà Nẵng</span>
        </div>
        <div class="contact-info">
          <i class="fas fa-phone"></i>
          <span>Hotline: 0236.3.888.130</span>
        </div>
        <div class="contact-info">
          <i class="fas fa-envelope"></i>
          <span>contact@iviettech.vn</span>
        </div>
        <div class="contact-info">
          <i class="fas fa-clock"></i>
          <span>T2-CN: 7:30 - 21:30</span>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2025 iViettech - Trung tâm Đào tạo Công nghệ Thông tin. Tất cả quyền được bảo lưu.</p>
      <p>Địa chỉ: 92 Quang Trung, Hải Châu, Đà Nẵng | Hotline: 0236.3.888.130</p>
    </div>
  </footer>

  <script>
    // Particles Animation
    function createParticles() {
      const container = document.getElementById('particles');
      const particleCount = 50;

      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';

        const size = Math.random() * 6 + 2;
        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDuration = (Math.random() * 10 + 5) + 's';
        particle.style.animationDelay = Math.random() * 5 + 's';

        container.appendChild(particle);
      }
    }

    // Header scroll effect
    window.addEventListener('scroll', function() {
      const header = document.getElementById('header');
      if (window.scrollY > 100) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });

    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Stats animation
    function animateStats() {
      const stats = document.querySelectorAll('.stat-number');

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const target = parseInt(entry.target.dataset.target);
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
              current += increment;
              if (current >= target) {
                current = target;
                clearInterval(timer);
              }
              entry.target.textContent = Math.floor(current).toLocaleString();
              if (target === 95) {
                entry.target.textContent = Math.floor(current) + '%';
              }
            }, 20);
          }
        });
      }, {
        threshold: 0.5
      });

      stats.forEach(stat => observer.observe(stat));
    }

    // Modal functionality
    let selectedRole = '';

    function openModal() {
      document.getElementById('loginModal').style.display = 'block';
      document.getElementById('roleSelection').style.display = 'block';
      document.getElementById('loginForm').classList.remove('show');
    }

    function closeModal() {
      document.getElementById('loginModal').style.display = 'none';
      resetModal();
    }

    function resetModal() {
      selectedRole = '';
      document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('active');
      });
      document.getElementById('roleSelection').style.display = 'block';
      document.getElementById('loginForm').classList.remove('show');
    }

    function selectRole(role) {
      selectedRole = role;
      document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('active');
      });
      document.querySelector(`.${role}-card`).classList.add('active');

      setTimeout(() => {
        document.getElementById('roleSelection').style.display = 'none';
        document.getElementById('loginForm').classList.add('show');
        document.getElementById('loginSubmit').className = `login-submit ${role}`;

        const roleNames = {
          student: 'Học viên',
          teacher: 'Giảng viên',
          admin: 'Quản trị viên'
        };

        document.querySelector('.modal-header h2').textContent = `Đăng nhập ${roleNames[role]}`;
      }, 300);
    }

    function backToRoleSelection() {
      document.getElementById('loginForm').classList.remove('show');
      setTimeout(() => {
        document.getElementById('roleSelection').style.display = 'block';
        document.querySelector('.modal-header h2').textContent = 'Đăng nhập hệ thống';
      }, 300);
    }

    // Form submission
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;

      if (username && password) {
        // Simulate login process
        const roleNames = {
          student: 'Học viên',
          teacher: 'Giảng viên',
          admin: 'Quản trị viên'
        };

        alert(`Đăng nhập thành công với vai trò: ${roleNames[selectedRole]}\nChào mừng bạn đến với hệ thống quản lý iViettech!`);
        closeModal();

        // Here you would normally redirect to the appropriate dashboard
        // window.location.href = `/dashboard/${selectedRole}`;
      }
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
      const modal = document.getElementById('loginModal');
      if (e.target === modal) {
        closeModal();
      }
    });

    // Course cards hover effect
    function addCourseHoverEffects() {
      const courseCards = document.querySelectorAll('.course-card');
      courseCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-10px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0) scale(1)';
        });
      });
    }

    // Initialize animations and effects
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
      animateStats();
      addCourseHoverEffects();

      // Intersection Observer for fade-in animations
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '0';
            entry.target.style.transform = 'translateY(50px)';
            entry.target.style.animation = 'fadeInUp 0.8s ease forwards';
          }
        });
      }, observerOptions);

      // Observe elements for animation
      document.querySelectorAll('.feature-card, .course-card, .testimonial-card, .notice-container').forEach(el => {
        observer.observe(el);
      });

      // Add typing effect to hero subtitle
      const heroSubtitle = document.querySelector('.hero-subtitle');
      const text = heroSubtitle.textContent;
      heroSubtitle.textContent = '';

      let i = 0;
      const typeWriter = () => {
        if (i < text.length) {
          heroSubtitle.textContent += text.charAt(i);
          i++;
          setTimeout(typeWriter, 100);
        }
      };

      setTimeout(typeWriter, 1000);
    });

    // Add scroll progress indicator
    function addScrollProgress() {
      const progressBar = document.createElement('div');
      progressBar.style.position = 'fixed';
      progressBar.style.top = '0';
      progressBar.style.left = '0';
      progressBar.style.width = '0%';
      progressBar.style.height = '3px';
      progressBar.style.background = 'linear-gradient(135deg, #2563eb, #0ea5e9)';
      progressBar.style.zIndex = '9999';
      progressBar.style.transition = 'width 0.3s ease';
      document.body.appendChild(progressBar);

      window.addEventListener('scroll', () => {
        const scrollPercent = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
        progressBar.style.width = scrollPercent + '%';
      });
    }

    // Add floating action button for quick contact
    function addFloatingActionButton() {
      const fab = document.createElement('div');
      fab.innerHTML = '<i class="fas fa-phone"></i>';
      fab.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        z-index: 1000;
        transition: all 0.3s ease;
      `;

      fab.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1)';
        this.style.boxShadow = '0 6px 25px rgba(16, 185, 129, 0.6)';
      });

      fab.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
        this.style.boxShadow = '0 4px 20px rgba(16, 185, 129, 0.4)';
      });

      fab.addEventListener('click', function() {
        window.open('tel:0236.3.888.130', '_self');
      });

      document.body.appendChild(fab);
    }

    // Initialize additional features
    window.addEventListener('load', function() {
      addScrollProgress();
      addFloatingActionButton();
    });
  </script>
</body>

</html>