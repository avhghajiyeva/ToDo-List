<!DOCTYPE html>
<html>
<head>
    <title>Community Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-blue-grey.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <style>
        html, body, h1, h2, h3, h4, h5 { font-family: "Open Sans", sans-serif; }
    </style>
</head>
<body class="w3-theme-l5">

<!-- Navbar -->
<div class="w3-top">
    <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
        <a class="w3-bar-item w3-button w3-right w3-padding-large w3-hover-white" href="<?= base_url("logout") ?>">Logout</a>
    </div>
</div>

<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
    <div class="w3-row">

        <!-- Left Column -->
        <div class="w3-col m3">
            <div class="w3-card w3-round w3-white">
                <div class="w3-container">
                    <h4 class="w3-center">My Profile</h4>
                    <hr>
                    <p><i class="fa fa-pencil fa-fw w3-margin-right w3-text-theme"></i> Adi</p>
                </div>
            </div>
            <br>
        </div>

        <!-- Middle Column -->
        <div class="w3-col m7">
            <div class="w3-row-padding">
                <div class="w3-col m12">
                    <div class="w3-card w3-round w3-white">
                        <div class="w3-container w3-padding">
                            <h6 class="w3-opacity">Create a Post</h6>
                            <form id="postForm" data-role="post-form" method="POST">
                                <input type="text" data-role="title" id="title" name="title" placeholder="Title" class="form-control mb-3" required><br>
                                <textarea name="description" data-role="description" id="description" rows="8" cols="80" placeholder="Description" class="form-control mb-3" required></textarea><br>
                                <button type="submit" class="btn btn-primary">Post it</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts Container -->
            <div id="postsContainer" data-role="post-container"></div>
        </div>

        <!-- Right Column -->
        <div class="w3-col m2">
            <div class="w3-card w3-round w3-white w3-center">
                <div class="w3-container"></div>
            </div>
            <br>
        </div>
    </div>
</div>
<br>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    const cardComponent = (v,i) => {
      return `
              <div class="w3-container w3-card w3-white w3-round w3-margin" data-id="${v.id}">
                <h4>${v.title}</h4>
                <hr class="w3-clear">
                <p>${v.description}</p>
                <p><strong>Likes: <span class="likeCount" data-role="like-count">${v.like_count}</span></strong></p>

                <button type="button" class="btn w3-margin-bottom likeBtn ${v.is_liked === "1" ? 'w3-theme-d1' : 'w3-grey'}" data-role="btn-like" onclick="likePost(this)">
                    <i class="fa fa-thumbs-up"></i> &nbsp;Like
                </button>
              </div>
              `;
    }

    const getContent = () => {
      let html = ``;

      $.get({
          url: '<?= base_url("community") ?>',
          dataType: 'json',
          success: function(response) {
              $(`[data-role="post-container"]`).empty();
              html += response.map((v,i) => cardComponent(v,i)).join(``);
              $(`[data-role="post-container"]`).html(html);
          },
          error: function() {
              alert('Error fetching posts');
          }
      });
    }

    getContent();

    $(`[data-role="post-form"]`).on('submit', function(e) {
        e.preventDefault();

        let data = {

        }

        $.post({
            url: '<?= base_url("submit-post") ?>',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    $(`[data-role="post-form"]`)[0].reset();
                    fetchPosts();
                }
            },
            error: function() {
                console.error("Error submitting post");
            }
        });
    });

// function likePost(button) {
//     let parent =  $(button).parents("div");
//     let post_id = parent.data("id");
//     let currentCount = +parent.find(`[data-role="like-count"]`).html() ;
//     $.ajax({
//         type: 'POST',
//         url: '<?= base_url("toggle-like") ?>',
//         data: { post_id: post_id },
//         dataType: 'json',
//         success: function(response) {
//             if (response.error) {
//                 alert(response.error);
//             } else {
//                 if (response.liked) {
//                     $(button).removeClass('w3-grey').addClass('w3-theme-d1');
//                     parent.find(`[data-role="like-count"]`).html(currentCount + 1);
//                 } else {
//                     $(button).removeClass('w3-theme-d1').addClass('w3-grey');
//                     parent.find(`[data-role="like-count"]`).html(currentCount - 1);
//                 }
//             }
//         },
//         error: function() {
//             alert('Error toggling like');
//         }
//     });
// }
function likePost(button) {
    let parent = $(button).closest("div"), // Find the closest div that represents the post
        post_id = parent.data("id"),
        currentCount = +parent.find(`[data-role="like-count"]`).text(), // Use .text() instead of .html() for fetching text
        isLiked = $(button).hasClass('w3-theme-d1'); // Check if the button has the 'liked' class

    let data = {
      post_id
    }

    $.post({
        url: '<?= base_url("toggle-like") ?>',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                alert(response.error);
            } else {
                // Update the like count and button style only for the current post
                if (response.liked) {
                    $(button).removeClass('w3-grey').addClass('w3-theme-d1'); // Change button color to indicate 'liked'
                    parent.find(`[data-role="like-count"]`).text(currentCount + 1); // Increment like count
                } else {
                    $(button).removeClass('w3-theme-d1').addClass('w3-grey'); // Change button color to indicate 'not liked'
                    parent.find(`[data-role="like-count"]`).text(currentCount - 1); // Decrement like count
                }
            }
        },
        error: function() {
            alert('Error toggling like');
        }
    });
}


</script>

</body>
</html>
