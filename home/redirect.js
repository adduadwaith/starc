function redirectUser(role) {
    let redirectURL = "dashboard.php"; // Default page
    if (role === "admin") {
        redirectURL = "admin_dashboard.php";
    } else if (role === "rider") {
        redirectURL = "rider_dashboard.php";
    } else if (role === "relative") {
        redirectURL = "relative_dashboard.php";
    }

    window.location.href = redirectURL;
}
