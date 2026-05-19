function addReview(menuItemId) {
    let comment = document.getElementById('comment').value.trim();
    let error = document.getElementById('reviewError');

    error.innerHTML = '';

    if (comment == '') {
        error.innerHTML = 'Comment cannot be empty.';
        return false;
    }

    if (comment.length > 500) {
        error.innerHTML = 'Comment must be within 500 characters.';
        return false;
    }

    let formData = new FormData();
    formData.append('menu_item_id', menuItemId);
    formData.append('comment', comment);

    let xhttp = new XMLHttpRequest();
    xhttp.open('POST', '../../api/add_review.php', true);

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let res = JSON.parse(this.responseText);

            if (res.status) {
                let review = res.review;
                let output = '';

                output += '<div class="review-box" id="review-' + review.id + '">';
                output += '<strong>' + review.member_name + '</strong>';
                output += '<p>' + review.comment + '</p>';
                output += '<small>' + review.created_at + '</small><br><br>';
                output += '<button class="btn btn-danger btn-sm" onclick="deleteReview(' + review.id + ')">Delete</button>';
                output += '</div>';

                document.getElementById('reviewList').innerHTML = output + document.getElementById('reviewList').innerHTML;
                document.getElementById('comment').value = '';
            } else {
                error.innerHTML = res.message;
            }
        }
    };

    xhttp.send(formData);
    return false;
}

function deleteReview(reviewId) {
    if (!confirm('Are you sure you want to delete this review?')) {
        return;
    }

    let formData = new FormData();
    formData.append('review_id', reviewId);

    let xhttp = new XMLHttpRequest();
    xhttp.open('POST', '../../api/delete_review.php', true);

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let res = JSON.parse(this.responseText);

            if (res.status) {
                let reviewBox = document.getElementById('review-' + reviewId);
                if (reviewBox) {
                    reviewBox.remove();
                }
            } else {
                alert(res.message);
            }
        }
    };

    xhttp.send(formData);
}
