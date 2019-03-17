//function to change the radio button according to nicer link designed with bootstrap
function changeCheckValue(item) {
    var value = (item.id.slice == "1");
    item.className = item.className.replace("btn-outline","btn");
    noItem = document.getElementById(item.id.slice(0,-1) + (!value | 0)  );
    if (noItem.className.search('outline') == -1) 
        noItem.className = noItem.className.replace("btn-", "btn-outline-");
    var input = document.getElementById(item.id.replace("button_",""));
    input.checked = true;
}