// lấy role từ URL
const params = new URLSearchParams(window.location.search);
const role = params.get("role");

document.getElementById("role").value = role;

// đổi title theo role
if (role === "admin") {
  document.getElementById("title").innerText = "Admin";
} else {
  document.getElementById("title").innerText = "Nhân viên";
}

// LOGIN
function login() {
  const role = document.getElementById("role").value;
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  fetch("php/login.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ role, username, password })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      window.location.href = data.redirect;
    } else {
      alert("Sai tài khoản hoặc mật khẩu");
    }
  });
}

// FORGOT PASSWORD
function forgotPassword() {
  const role = document.getElementById("role").value;
  const username = document.getElementById("username").value;

  if (!username) {
    alert("Nhập email/tài khoản trước");
    return;
  }

  fetch("php/forgot_password.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ role, username })
  })
  .then(res => res.json())
  .then(data => {
    alert(data.message);
  });
}
function goBack() {
  if (document.referrer) {
    window.history.back();
  } else {
    window.location.href = "../Trang_chu.html";
  }
}