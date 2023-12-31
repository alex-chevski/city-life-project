/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import jQuery from "jquery";
window.$ = jQuery;

import "./bootstrap";
import "summernote/dist/summernote-lite.js";

$(document).on("click", ".phone-button", function () {
  var button = $(this);
  axios
    .post(button.data("source"))
    .then(function (response) {
      button.find(".number").html(response.data);
    })
    .catch(function (error) {
      console.error(error);
    });
});

$(".banner").each(function () {
  var block = $(this);
  var url = block.data("url");
  var format = block.data("format");
  var category = block.data("category");
  var region = block.data("region");

  axios
    .get(url, {
      params: {
        format: format,
        category: category,
        region: region,
      },
    })
    .then(function (response) {
      block.html(response.data);
    })
    .catch(function (error) {
      console.error(error);
    });
});

//summernote redactor
$(".summernote").summernote({
  height: 300,
  lang: "es-ES",
  callbacks: {
    onImageUpload: function (files) {
      var editor = $(this);
      var url = editor.data("image-url");
      var data = new FormData();
      data.append("file", files[0]);
      axios
        .post(url, data)
        .then(function (response) {
          editor.summernote("insertImage", response.data);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
  },
});
