const usersDB = [
  { username: "admin", password: "admin123" },
  { username: "user", password: "user123" },
];

document.querySelector("form").addEventListener("submit", function (e) {
  e.preventDefault();
  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value;

  const user = usersDB.find(
    (u) => u.username === username && u.password === password
  );

  if (user) {
    alert("Login successful!");
    // Redirect or show logged-in content here
  } else {
    alert("Invalid username or password.");
  }
});
