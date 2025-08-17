<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

$error = ""; // biến để hiển thị thông báo lỗi

if (isset($_POST['login'])) {
  $stuid = $_POST['stuid'];
  $password = md5($_POST['password']);
  $sql = "SELECT StuID,ID,StudentClass FROM tblstudent WHERE (UserName=:stuid OR StuID=:stuid) and Password=:password";
  $query = $dbh->prepare($sql);
  $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
  $query->bindParam(':password', $password, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);

  if ($query->rowCount() > 0) {
    foreach ($results as $result) {
      $_SESSION['sturecmsstuid'] = $result->StuID;
      $_SESSION['sturecmsuid'] = $result->ID;
      $_SESSION['stuclass'] = $result->StudentClass;
    }

    if (!empty($_POST["remember"])) {
      setcookie("user_login", $_POST["stuid"], time() + (10 * 365 * 24 * 60 * 60));
      setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
    } else {
      if (isset($_COOKIE["user_login"])) {
        setcookie("user_login", "", time() - 3600);
        if (isset($_COOKIE["userpassword"])) {
          setcookie("userpassword", "", time() - 3600);
        }
      }
    }
    $_SESSION['login'] = $_POST['stuid'];

    // ✅ Redirect an toàn
    header("Location: dashboard.php");
    exit();
  } else {
    $error = "❌ Thông tin đăng nhập không chính xác!";
  }
}
?>

<!DOCTYPE html>
<html lang="vi">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập Học viên - iViettech</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }


    :root {
      --primary-color: #0ea5e9;
      --secondary-color: #06b6d4;
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
      background: #8f8f8fff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }


    /* Particles Background */
    .particles-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background: #8f8f8fff;
    }


    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      pointer-events: none;
      animation: float 8s infinite linear;
    }


    @keyframes float {
      0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
      }


      10% {
        opacity: 1;
      }


      90% {
        opacity: 1;
      }


      100% {
        transform: translateY(-100vh) rotate(360deg);
        opacity: 0;
      }
    }


    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }


      to {
        opacity: 1;
        transform: translateY(0);
      }
    }


    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }


      to {
        opacity: 1;
        transform: translateX(0);
      }
    }


    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 3rem;
      width: 90%;
      max-width: 450px;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: fadeIn 0.6s ease;
    }


    .login-header {
      text-align: center;
      margin-bottom: 2.5rem;
      animation: slideInLeft 0.8s ease;
    }


    .logo-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      color: white;
      font-size: 2rem;
      box-shadow: var(--shadow);
      animation: fadeIn 1s ease 0.2s both;
    }


    .login-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
      animation: fadeIn 1s ease 0.4s both;
    }


    .login-subtitle {
      color: var(--text-secondary);
      font-size: 1rem;
      animation: fadeIn 1s ease 0.6s both;
    }


    .role-badge {
      display: inline-block;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 600;
      margin-bottom: 1rem;
      animation: fadeIn 1s ease 0.8s both;
    }


    .form-group {
      margin-bottom: 1.5rem;
      animation: fadeIn 1s ease 1s both;
    }


    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--text-primary);
      font-size: 0.875rem;
    }


    .input-wrapper {
      position: relative;
    }


    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      font-size: 1.125rem;
      z-index: 2;
    }


    .form-input {
      width: 100%;
      padding: 1rem 1rem 1rem 3rem;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
    }


    .form-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
      background: rgba(255, 255, 255, 0.95);
    }


    .form-input::placeholder {
      color: var(--text-secondary);
    }


    .checkbox-wrapper {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 1.5rem 0;
      animation: fadeIn 1s ease 1.2s both;
    }


    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }


    .checkbox-group input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: var(--primary-color);
    }


    .checkbox-group label {
      font-size: 0.875rem;
      color: var(--text-secondary);
      margin: 0;
    }


    .forgot-link {
      color: var(--primary-color);
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 500;
      transition: color 0.3s ease;
    }


    .forgot-link:hover {
      color: var(--secondary-color);
    }


    .login-button {
      width: 100%;
      padding: 1rem;
      border: none;
      border-radius: 12px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 1.5rem;
      color: white;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      box-shadow: var(--shadow);
      animation: fadeIn 1s ease 1.4s both;
    }


    .login-button:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }


    .login-button:active {
      transform: translateY(0);
    }


    .back-button {
      width: 100%;
      padding: 1rem;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.8);
      color: var(--text-primary);
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      animation: fadeIn 1s ease 1.6s both;
    }


    .back-button:hover {
      border-color: var(--primary-color);
      background: rgba(14, 165, 233, 0.05);
      transform: translateY(-1px);
    }


    .alert {
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      color: var(--danger-color);
      font-size: 0.875rem;
      animation: fadeIn 0.3s ease;
    }


    @media (max-width: 640px) {
      .login-container {
        padding: 2rem;
        margin: 1rem;
        border-radius: 16px;
      }


      .login-title {
        font-size: 1.5rem;
      }


      .logo-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
      }
    }
  </style>
</head>


<body>
  <!-- Particles Background -->
  <div class="particles-bg">
    <script>
      // Create floating particles
      function createParticle() {
        const particle = document.createElement('div');
        particle.className = 'particle';


        const size = Math.random() * 4 + 2;
        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = (Math.random() * 10 + 10) + 's';


        document.querySelector('.particles-bg').appendChild(particle);


        setTimeout(() => {
          particle.remove();
        }, 20000);
      }


      // Create particles periodically
      setInterval(createParticle, 300);
    </script>
  </div>

  <div class="login-container">
    <div class="login-header">
      <div class="logo-icon">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <h1 class="login-title">Chào mừng trở lại!</h1>
      <p class="login-subtitle">Đăng nhập vào tài khoản học viên của bạn</p>
      <div class="role-badge">
        <i class="fas fa-user-graduate"></i> Học viên
      </div>
    </div>

    <!-- Hiển thị lỗi -->
    <?php if (!empty($error)): ?>
      <div class="alert"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" id="loginForm">
      <div class="form-group">
        <label for="stuid">Mã học viên hoặc tên đăng nhập</label>
        <div class="input-wrapper">
          <i class="fas fa-user input-icon"></i>
          <input
            type="text"
            id="stuid"
            name="stuid"
            class="form-input"
            placeholder="Nhập mã học viên hoặc tên đăng nhập"
            required
            value="<?php if (isset($_COOKIE["user_login"])) echo $_COOKIE["user_login"]; ?>">
        </div>
      </div>

      <div class="form-group">
        <label for="password">Mật khẩu</label>
        <div class="input-wrapper">
          <i class="fas fa-lock input-icon"></i>
          <input
            type="password"
            id="password"
            name="password"
            class="form-input"
            placeholder="Nhập mật khẩu của bạn"
            required
            value="<?php if (isset($_COOKIE["userpassword"])) echo $_COOKIE["userpassword"]; ?>">
        </div>
      </div>

      <div class="checkbox-wrapper">
        <div class="checkbox-group">
          <input type="checkbox" id="remember" name="remember" <?php if (isset($_COOKIE["user_login"])) echo "checked"; ?>>
          <label for="remember">Ghi nhớ đăng nhập</label>
        </div>
        <a href="forgot-password.php" class="forgot-link">Quên mật khẩu?</a>
      </div>

      <button type="submit" name="login" id="loginBtn" class="login-button">
        <i class="fas fa-sign-in-alt"></i> Đăng nhập
      </button>

      <a href="../index.php" class="back-button">
        <i class="fas fa-home"></i> Về trang chủ
      </a>
    </form>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function() {
      const btn = document.getElementById('loginBtn');
      btn.classList.add('loading');
      setTimeout(() => {
        btn.classList.remove('loading');
      }, 3000);
    });

    // Auto-focus first input
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('stuid').focus();
    });
  </script>
</body>

</html>