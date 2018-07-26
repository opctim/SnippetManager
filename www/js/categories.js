$(document).ready(function(){
    var searchField = $("#search-field");

    if (searchField.val().trim() !== "")
        reloadCategories();

    var timeout = null;

    searchField.keyup(function(){
        if (timeout !== null)
            clearTimeout(timeout);

        var $this = $(this);

        timeout = setTimeout(function(){
            var spinner = $this.siblings(".fa");

            spinner.show();

            reloadCategories(function(){
                spinner.hide();
            });
        }, 600);
    });

    var newCategoryElement = $("#new-category");

    function resetNewCategoryForm() {
        var form = newCategoryElement.children("form");

        $(".select2:not(.tags) option").prop("selected", false);
        $(".select2:not(.tags) option:first-child").prop("selected", true);
        $(".select2:not(.tags)").trigger("change");
        $(".select2.tags").empty().trigger("change");
        form.get(0).reset();
    }

    newCategoryElement.find("form .close-btn").click(function(){
        var form = $(this).parent("form");

        $("#new-category").fadeOut();

        resetNewCategoryForm();
    });

    $(".add-category").click(function(){
        $("#new-category").fadeIn();
    });

    newCategoryElement.submit(function(e){
        e.preventDefault();

        var form = newCategoryElement.find("form");

        form.css({
            filter: "blur(5px)",
            opacity: "0.75"
        });

        $.ajax({
            url: "index.php",
            data: {
                newCategory: {
                    name: form.find(':input[name="name"]').val(),
                    description: form.find(':input[name="description"]').val(),
                    color: form.find(':input[name="color"]').val()
                }
            },
            success: function(response){
                reloadCategories(function(){
                    newCategoryElement.fadeOut();
                    resetNewCategoryForm();

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

    function reloadCategories(done) {
        $.ajax({
            url: "index.php",
            data: {
                categories: $("#search-field").val()
            },
            success: function(html){
                var categoryList = $("#category-list");

                if (html !== lastHtml) {
                    lastHtml = html;

                    categoryList.html(html);
                }

                if (typeof done !== "undefined")
                    done();

                $("#category-count").html(categoryList.find(".category").length);
            }
        });
    }

    if (document.addEventListener) {
        document.addEventListener("contextmenu", function(e) {
            if ($(e.target).parents(".category-link").length > 0) {
                onContextMenuRequested(e);
                e.preventDefault();
            }
        }, false);
    }
    else {
        document.attachEvent("oncontextmenu", function() {
            if ($(window.event.target).parents(".category-link").length > 0) {
                onContextMenuRequested(window.event);
                window.event.returnValue = false;
            }
        });
    }

    var contextMenuActiveElement = null;

    function onContextMenuRequested(e) {
        $("#context-menu").css({
            top: e.clientY,
            left: e.clientX
        }).fadeIn();

        contextMenuActiveElement = $(e.target).parents(".category-link");
    }

    var contextMenu = $("#context-menu");

    contextMenu.on("click", function(e){
        e.stopPropagation();
    });

    $(document).on("click", function(){
        var contextMenu = $("#context-menu");

        if (contextMenu.is(":visible"))
            contextMenu.hide();
    });

    contextMenu.find("li").click(function(){
        contextMenu.hide();

        if (contextMenuActiveElement !== null) {
            if ($(this).hasClass("edit")) {
                location.href = contextMenuActiveElement.prop("href");
            }
            else if ($(this).hasClass("show-snippets")) {
                location.href = "/index.php?snippet-search=" + encodeURIComponent('category:"' + contextMenuActiveElement.data("cn") + '"');
            }
            else if ($(this).hasClass("delete")) {
                var deleteConfirm = $("#delete-confirm");

                deleteConfirm.css({
                    filter: "none",
                    opacity: "1"
                });
                deleteConfirm.fadeIn();

                deleteConfirm.find("button").one("click", function(e){
                    e.preventDefault();

                    if ($(this).hasClass("yes")) {
                        deleteConfirm.css({
                            filter: "blur(5px)",
                            opacity: "0.75"
                        });

                        $.ajax({
                            url: "index.php",
                            data: {
                                deleteCategory: contextMenuActiveElement.data("cid")
                            },
                            success: function(response){
                                reloadCategories(function(){
                                    deleteConfirm.fadeOut();

                                    deleteConfirm.css({
                                        filter: "none",
                                        opacity: "1"
                                    });
                                });
                            }
                        });
                    }
                    else {
                        deleteConfirm.fadeOut();
                    }

                    return false;
                });
            }
        }
    });
});