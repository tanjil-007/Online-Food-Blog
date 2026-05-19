function escapeHtml(text) {
    if (text == null) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function searchData() {
    let q = document.getElementById('q').value;
    let location = document.getElementById('location').value;
    let area = document.getElementById('area').value;

    let xhttp = new XMLHttpRequest();
    xhttp.open('GET', '../../api/search.php?q=' + encodeURIComponent(q) + '&location=' + encodeURIComponent(location) + '&area=' + encodeURIComponent(area), true);

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.responseText);
            let output = '';

            if (data.length == 0) {
                output = '<div class="card" style="text-align:center; padding:2rem; color:#888;">No result found.</div>';
            } else {
                for (let i = 0; i < data.length; i++) {
                    output += '<div class="restaurant-card">';
                    output += '<h3>' + escapeHtml(data[i].restaurant_name) + '</h3>';
                    output += '<div class="meta">📍 ' + escapeHtml(data[i].location) + ' — ' + escapeHtml(data[i].area) + '</div>';
                    output += '<p style="color:#555; font-size:.9rem; line-height:1.5; margin-bottom:1rem;">' + escapeHtml(data[i].short_background).substring(0, 120) + '...</p>';
                    output += '<a href="detail.php?id=' + data[i].restaurant_id + '" class="btn btn-primary btn-sm">View Restaurant →</a>';

                    if (data[i].item_id != null) {
                        output += '<div style="margin-top:1rem; padding-top:1rem; border-top:1px solid #eee;">';
                        output += '<strong>Food Item:</strong> ' + escapeHtml(data[i].item_name) + '<br>';
                        output += '<span>Price: ৳' + escapeHtml(data[i].price) + '</span><br><br>';
                        output += '<a href="../menu/detail.php?id=' + data[i].item_id + '" class="btn btn-secondary btn-sm">View Item</a>';
                        output += '</div>';
                    }

                    output += '</div>';
                }
            }

            document.getElementById('restaurantResult').innerHTML = output;
        }
    };

    xhttp.send();
}
