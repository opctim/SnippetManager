/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 17:43
 */


$(document).ready(function(){
    $(document).on("click", "pre", function(){
        var range;

        if (this.select) {
            this.select();
        }
        else if (document.selection) {
            range = document.body.createTextRange();
            range.moveToElementText(this);
            range.select();
        }
        else if (window.getSelection) {
            range = document.createRange();
            range.selectNode(this);
            window.getSelection().addRange(range);
        }
    });

    $(document).on("click", "pre ~ .overlay", function(){
        $(this).siblings("pre").trigger("click");
    });

    var searchField = $("#search-field");

    if (searchField.val().trim() !== "")
        reloadSnippets();

    var timeout = null;

    searchField.keyup(function(){
        if (timeout !== null)
            clearTimeout(timeout);

        var $this = $(this);

        timeout = setTimeout(function(){
            var spinner = $this.siblings(".fa");

            spinner.show();

            reloadSnippets(function(){
                spinner.hide();
            });
        }, 600);
    });

    var lastHtml = null;

    function reloadSnippets(done) {
        $.ajax({
            url: "index.php",
            data: {
                snippets: $("#search-field").val()
            },
            success: function(html){
                var snippetList = $("#snippet-list");

                if (html !== lastHtml) {
                    lastHtml = html;

                    snippetList.html(html);
                }

                if (typeof done !== "undefined")
                    done();
            }
        });
    }
});
