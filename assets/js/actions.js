document.getElementById("tabLogin").addEventListener('click', (e) => {
    document.getElementById("loginForm").classList.remove("hidden");
    document.getElementById("loginForm").classList.add("show");
    document.getElementById("signForm").classList.add('hidden');
    document.getElementById("signForm").classList.remove('show');
});
document.getElementById("tabSignup").addEventListener('click', (e) => {
    document.getElementById("signForm").classList.remove("hidden");
    document.getElementById("signForm").classList.add("show");
    document.getElementById("loginForm").classList.remove("show");
    document.getElementById("loginForm").classList.add("hidden");
});

document.getElementsByName('login')[0].addEventListener('input', () => {
    let logToCheck = document.getElementsByName('login')[0].value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            switch (this.response) {
                case ('true'):
                    alert("This login exist \r change it or signin !");
                    break;
                case ('error'):
                    alert("alphanumeric character only !");
                    document.getElementsByName('login')[0].value = logToCheck.slice(0, -1);
                    break;
            }
        }
    }
    xmlhttp.open("GET", `members.php?action=checkMember&login=${logToCheck}`);
    xmlhttp.send();

});
document.getElementsByName('userlogin')[0].addEventListener('input', () => {
    let logToCheck = document.getElementsByName('userlogin')[0].value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            switch (this.response) {
                case ('error'):
                    alert("alphanumeric character only !");
                    document.getElementsByName('userlogin')[0].value = logToCheck.slice(0, -1);
                    break;
            }
        }
    }
    xmlhttp.open("GET", `members.php?action=checkMember&login=${logToCheck}`);
    xmlhttp.send();

});