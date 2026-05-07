<!-- thanh_phan/footer.php -->

</div>
<script>

function openModal(){

    document
    .getElementById("modalThem")
    .style.display = "block";

}

function closeModal(){

    document
    .getElementById("modalThem")
    .style.display = "none";

}

window.onclick = function(event){

    let modal =
    document.getElementById("modalThem");

    if(event.target == modal){

        modal.style.display = "none";

    }

}

</script>
</body>
</html>