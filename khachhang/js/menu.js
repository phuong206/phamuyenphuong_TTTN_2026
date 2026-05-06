function tang(btn){
    let i = btn.parentElement.querySelector("input");
    i.value = parseInt(i.value)+1;
}

function giam(btn){
    let i = btn.parentElement.querySelector("input");
    if(i.value>0) i.value--;
}