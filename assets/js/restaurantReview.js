function addRestaurantReview(){

    let restaurant_id = document.getElementById('restaurant_id').value;
    let rating = document.getElementById('rating').value;
    let comment = document.getElementById('restaurant_comment').value;

    if(comment == ""){
        alert("Comment cannot be empty.");
        return false;
    }

    if(comment.length > 500){
        alert("Comment must be within 500 characters.");
        return false;
    }

    let xhttp = new XMLHttpRequest();

    xhttp.open("POST", "../../api/add_restaurant_review.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            let response = JSON.parse(this.responseText);

            alert(response.message);

            if(response.status == true){
                location.reload();
            }
        }
    };

    xhttp.send(
        "restaurant_id=" + restaurant_id +
        "&rating=" + rating +
        "&comment=" + encodeURIComponent(comment)
    );
}


function deleteRestaurantReview(review_id){

    let confirmDelete = confirm("Are you sure you want to delete this review?");

    if(confirmDelete == false){
        return false;
    }

    let xhttp = new XMLHttpRequest();

    xhttp.open("POST", "../../api/delete_restaurant_review.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            let response = JSON.parse(this.responseText);

            alert(response.message);

            if(response.status == true){
                location.reload();
            }
        }
    };

    xhttp.send("review_id=" + review_id);
}