/*(function(){var a={exec:function(b){b.openDialog("recordmp3js");return}};CKEDITOR.plugins.add("recordmp3js",{lang:["en","zh"],requires:["dialog"],init:function(c){var b="recordmp3js";c.addCommand(b,a);c.ui.addButton("recordmp3js",{label:c.lang.youtube.button,command:b,icon:this.path+"images/mic.png"});CKEDITOR.dialog.add(b,CKEDITOR.getUrl(this.path+"dialogs/recordmp3js.js"))}})})();*/


(function() {
    var a = {
        exec: function(b) {
            b.openDialog("recordmp3js");
            return
        }
    };
    CKEDITOR.plugins.add("recordmp3js", {
        lang: ["en", "zh"],
        requires: ["dialog"],
        init: function(c) {
            var b = "recordmp3js";
            c.addCommand(b, a);
            c.ui.addButton("recordmp3js", {
                // label: c.lang.youtube.button,
                label: 'Record MP3',
                command: b,
                icon: this.path + "images/mic.png"
            });
            CKEDITOR.dialog.add(b, CKEDITOR.getUrl(this.path + "dialogs/recordmp3js.js"))
        }
    })
}
)();