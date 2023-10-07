$(".menu > ul > li").click(function (e) {
    // remove active from already active
    $(this).siblings().removeClass("active");
    // add active to clicked
    $(this).toggleClass("active");
    // if has sub menu open it
    $(this).find("ul").slideToggle();
    // close other sub menu if any open
    $(this).siblings().find("ul").slideUp();
    // remove active class of sub menu items
    $(this).siblings().find("ul").find("li").removeClass("active");
});

$(".menu-btn").click(function() {
    $(".sidebar").toggleClass("active")
})
let loadOnce = true;
// Add an event listener for changes in window width
window.addEventListener('resize', function() {
  if (window.innerWidth < 768) {
    if(loadOnce){
        $(".sidebar").addClass("active");
        loadOnce = false;
    }
  }else{
    loadOnce = true;
  }
});
var icon = document.getElementById("icon");
icon.onclick = function() {
    document.body.classList.toggle("dark-theme");
    if(document.body.classList.contains("dark-theme")){
        icon.src = "../vendor/images/sun.png";
        $('.theme').html('Light Mode');
    }else{
        icon.src = "../vendor/images/moon.png";
        $('.theme').html('Dark Mode');
    }
}
getDaytimeIcon();
function getDaytimeIcon() {
    const now = new Date();
    const currentHour = now.getHours();
    if (currentHour >= 20 || currentHour < 5) {
        // It's nighttime (8 PM to 5 AM)
        document.body.classList.toggle("dark-theme");
        if(document.body.classList.contains("dark-theme")){
            icon.src = "../vendor/images/sun.png";
            $('.theme').html('Light Mode');
        }else{
            icon.src = "../vendor/images/moon.png";
            $('.theme').html('Dark Mode');
        }
    } else {
        // It's daytime (5 AM to 8 PM)
        if(document.body.classList.contains("dark-theme")){
            icon.src = "../vendor/images/sun.png";
            $('.theme').html('Light Mode');
        }else{
            icon.src = "../vendor/images/moon.png";
            $('.theme').html('Dark Mode');
        }
    }
}

dateTime();
function dateTime() {
    $('#datetime').html(
        new Date().toLocaleString("en-US", {
            dateStyle: 'full',
            timeStyle: 'medium',
        }).replace(/(.*)at(.*)/, "$1-$2")
    );
    setTimeout(dateTime, 1000);
}
function deleteEmail(){
    alert("deleted!")
}
function deleteEmail(removeEmail){
  // Find the parent email element of the clicked trash icon
  var emailElement = removeEmail.closest(".email");
  // Check if the email element exists before attempting to remove it
  if (emailElement) {
    // Remove the email element from the DOM
    emailElement.remove();
  }
}
$('#example').DataTable({
    'responsive': true,
    'autoWidth': false,
});