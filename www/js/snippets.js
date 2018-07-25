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

    var newSnippetElement = $("#new-snippet");

    function resetNewSnippetForm() {
        var form = newSnippetElement.children("form");

        $(".select2:not(.tags) option").prop("selected", false);
        $(".select2:not(.tags) option:first-child").prop("selected", true);
        $(".select2:not(.tags)").trigger("change");
        $(".select2.tags").empty().trigger("change");
        form.get(0).reset();
    }

    newSnippetElement.find("form .close-btn").click(function(){
        var form = $(this).parent("form");

        $("#new-snippet").fadeOut();

        resetNewSnippetForm();
    });

    $(".add-snippet").click(function(){
        $("#new-snippet").fadeIn();
    });

    newSnippetElement.submit(function(e){
        e.preventDefault();

        var form = newSnippetElement.find("form");

        form.css({
            filter: "blur(5px)",
            opacity: "0.75"
        });

        $.ajax({
            url: "index.php",
            data: {
                newsnippet: {
                    categoryId: form.find(':input[name="category"]').val(),
                    name: form.find(':input[name="name"]').val(),
                    text: form.find(':input[name="text"]').val(),
                    tags: form.find(':input[name="tags"]').val().join(" ")
                }
            },
            success: function(response){
                reloadSnippets(function(){
                    newSnippetElement.fadeOut();
                    resetNewSnippetForm();

                    form.css({
                        filter: "none",
                        opacity: "1"
                    });
                })
            }
        });

        return false;
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
