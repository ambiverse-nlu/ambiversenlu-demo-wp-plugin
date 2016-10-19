(function( $ ) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    var typeColors = {
        Person: "blue",
        Artist: "blue",
        Organization: "light-blue",
        Location: "green",
        Artifact: "light-green",
        Event: "orange",
        Other: "red",
        Unknown: "gray",
    };


    var mentions = [];
    var mentionsWithMeta = [];
    var text = '';
    var entityMetadata = {};
    var renderedEntities = [];
    var thresholdSlider;

    var state = $.deparam.querystring();


    var unknownImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M-1-1h62v62H-1z%22%2F%3E%3Ctext%20transform%3D%22translate(19.543%2044.675)%22%20fill%3D%22%23ABABAB%22%20font-family%3D%22%27MyriadPro-Bold%27%22%20font-size%3D%2250%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E";
    $(function() {

        $('#ambiverse-text-input').autogrow({vertical: true, horizontal: false});

        $('#ambiverse-text-input').bind('input propertychange change keyup', function() {
            updateState();
        });


        $("#analyze").click(function () {
            analyze_text();

            //Log the event in Google Analytics
            ga('send', 'event', 'Buttons', 'clicked', 'Analyze Button');
        });

        $("#settings-language").change(function() {
            updateState();
            analyze_text();
        });

        // With JQuery
        thresholdSlider =  $('#settings-threshold').slider({
            formatter: function(value) {
                return 'Current value: ' + value;
            }
        });


        $(thresholdSlider).change(function(e){

            $("#threshold-val").text(e.value.newValue);
            updateState();
            analyze_text();
        });


        $(document).on('mousedown', 'span.mention', function(e){
            var mention = $(this);
            var boxToSelect = null;
            var id = $(this).data("id");
            select_mention_and_box(mention, boxToSelect, id);
        });

        $(document).on('mousedown', '.entity-box', function(e){
            var mention = null;
            var boxToSelect = $(this);
            var id = $(this).data("id");
            select_mention_and_box(mention, boxToSelect, id);
        });

        if(state.text && state.text !== "") {
            $('#ambiverse-text-input').val(state.text);
        }

        if(state.language && state.language !== "") {
            $('#settings-language').val(state.language);
        }

        if(state.confidenceThreshold && state.confidenceThreshold !== "") {
            thresholdSlider.slider('setValue',parseFloat(state.confidenceThreshold));
            $("#threshold-val").text(state.confidenceThreshold);
        }

        if(state.analyze && state.analyze === "true") {
            analyze_text();
        }

    });

    function select_mention_and_box( mention, box, id ) {

        var mentions = [];
        var boxes = [];
        if(mention === null) {
            $("#ambiverse-annotated-text span.mention").each(function () {
                var idTmp = $(this).data("id");
                if (idTmp === id) {
                    mentions.push(this);
                }
            });
        } else {
            mentions.push(mention);
        }

        if(box=== null) {
            $("#ambiverse-result-entities .entity-box").each(function () {
                var idTmp = $(this).data("id");
                if (idTmp === id) {
                    boxes.push(this);
                }
            });
        } else {
            boxes.push(box);
        }

        mentions.forEach(function(value, key){
            $(value).addClass("selected");
        });

        boxes.forEach(function(value, key){
            $(value).addClass("selected");
        });


        $("span.mention").bind("mouseup touchend", function(e){
            var mention = $(this);
            e.stopPropagation();
            $("span.mention").unbind('mouseup touchend');
        });

        $("#ambiverse-annotated-text:not(span.mention)").bind("mousedown touchstart", function(e){

            mentions.forEach(function(value, key){
                $(value).removeClass("selected");
            });

            boxes.forEach(function(value, key){
                $(value).removeClass("selected");
            });

            $("#ambiverse-annotated-text:not(span.mention)").bind("mouseup touchend", function(e){
                $("#ambiverse-annotated-text:not(span.mention)").unbind('mouseup touchend');
            });
        });

        $("#ambiverse-result-entities .entity-box").bind("mouseup touchend", function(e){
            var mention = $(this);
            e.stopPropagation();
            $("#ambiverse-result-entities .entity-box").unbind('mouseup touchend');
        });

        $("#ambiverse-result-entities:not(.entity-box)").bind("mousedown touchstart", function(e){

            mentions.forEach(function(value, key){
                $(value).removeClass("selected");
            });

            boxes.forEach(function(value, key){
                $(value).removeClass("selected");
            });

            $("#ambiverse-result-entities:not(.entity-box)").bind("mouseup touchend", function(e){
                $("#ambiverse-result-entities:not(.entity-box)").unbind('mouseup touchend');
            });
        });
    }


    function get_entity_metadata(entities) {

        var entityIds = [];

        entities.forEach(function(value, key, mentions) {
            if(!jQuery.isEmptyObject(value)) {
                entityIds.push(value.id);
            }
        });

        //console.log(entityIds);
        $.ajax({
            type : "post",
            dataType : "json",
            url : ajax_obj.ajax_url,
            data : {
                action: "tag_entity_metadata",
                entities : entityIds,
                _ajax_nonce: ajax_obj.nonce
            },
            success: function(data) {
                //console.log(data);
                $("#ambiverse-json-output-meta code").text(JSON.stringify(data, null, 2));
                $('#ambiverse-json-output-meta code').each(function(i, block) {
                    hljs.highlightBlock(block);
                });

                if(typeof data !=='undefined' && data !== null) {
                    var entitiesTmp = data["entities"];
                    if (typeof entitiesTmp !== 'undefined' && entitiesTmp !== 'null' && entitiesTmp.length > 0) {
                        //hash the entity metadata
                        entitiesTmp.forEach(function (value, key) {
                            entityMetadata[value.id] = value;
                        });
                    }
                }

                var allEntities = [];
                var entitiesWithconfidences = {};

                var mentionsCopy = clone(mentions);
                mentionsCopy.forEach(function (mention, key) {
                   if(!jQuery.isEmptyObject(mention["entity"])) {

                       var confidence = mention["entity"].confidence;

                       if(typeof entityMetadata[mention["entity"].id] !== 'undefined') {
                           mention["entity"] = entityMetadata[mention["entity"].id];
                       }
                       if(mention["entity"].id in entitiesWithconfidences) {
                           confidence = Math.max(confidence, entitiesWithconfidences[mention["entity"].id]);
                       }

                       mention["entity"]["confidence"] = confidence;
                       entitiesWithconfidences[mention["entity"].id] = confidence;
                       allEntities.push(entityMetadata[mention["entity"].id]);


                   } else {

                       var entity = {};
                       entity["id"] = mention["text"];
                       //entity["confidence"] = 0;
                       entity["name"] = mention.text;
                       mention["entity"] = entity;
                       allEntities.push(entity);
                   }

                });

                $("#ambiverse-annotated-text").html(annotate_text(mentionsCopy));
                $("#ambiverse-result-entities").html(entity_view(allEntities));
            },
            error : function(xhr, textStatus, errorThrown) {

            },
            beforeSend: function() {
                $("#ambiverse-json-output-meta code").text("");
                 $("#ambiverse-result-entities-loader").isLoading({
                     text: "Loading Entity Metadata ...",
                     position: "inside"
                 });
                $("#ambiverse-json-meta-loader").isLoading({
                    text: "Loading Entity Metadata JSON ...",
                    position: "inside"
                });
            },
            complete: function() {
                $("#ambiverse-result-entities-loader").isLoading("hide");
                $("#ambiverse-json-meta-loader").isLoading("hide");
            }
        })
    }

    function analyze_text() {
        var l = Ladda.create(  document.querySelector('.progress-button') );

        var textInput = $("#ambiverse-text-input");
        var coherentDocument = $(textInput).data("coherent-document"); //$("#settings-coherent").prop("checked");
        var confidenceThreshold = thresholdSlider.slider('getValue');
        var language = $("#settings-language").val();
        $.ajax({
            type : "post",
            dataType : "json",
            url : ajax_obj.ajax_url,
            data : {
                action: "tag_analyze_document",
                text : $(textInput).val(),
                coherentDocument : coherentDocument,
                confidenceThreshold : confidenceThreshold,
                language : language,
                _ajax_nonce: ajax_obj.nonce
            },
            success: function(data) {
                //console.log(data);

                if(typeof data["code"]!=='undefined' && data["code"]!==200) {

                    $("#ambiverse-annotated-text").removeClass("well");
                    $("#ambiverse-annotated-text").addClass("alert alert-danger");
                    $("#ambiverse-annotated-text").html(data["message"]);
                } else {
                    text = $(textInput).val();
                    text = text.replaceAll("[[", "");
                    text = text.replaceAll("]]", "");

                    var allEntities = [];
                    mentions = data["matches"];
                    if (typeof mentions !== 'undefined') {
                        mentions.forEach(function (value, key, mentions) {
                            allEntities.push(value["entity"]);
                        });
                    }

                    //Add the text to the json output and highlight the block
                    $("#ambiverse-json-output code").text(JSON.stringify(data, null, 2));
                    $('#ambiverse-json-output code').each(function (i, block) {
                        hljs.highlightBlock(block);
                    });

                    $("#ambiverse-annotated-text").html(annotate_text(mentions));

                     //Get entity metadata for all entities in the text
                     get_entity_metadata(allEntities);
                }
            },
            error : function(xhr, textStatus, errorThrown) {

            },
            beforeSend: function() {
                if($("#ambiverse-annotated-text").hasClass("alert")) {
                    $("#ambiverse-annotated-text").removeClass("alert");
                    $("#ambiverse-annotated-text").removeClass("alert-danger");
                    $("#ambiverse-annotated-text").addClass("well");
                }

                $("#ambiverse-json-output code").text("");
                $("#ambiverse-annotated-text").html("");
                $("#ambiverse-result-entities").html("");

                $("#result-wrapper").css('display', 'block');
                l.start();

                $("#ambiverse-annotated-text").isLoading({
                    text: "Analyzing Text ...",
                    position: "inside"
                });

                $("#ambiverse-json-linking-loader").isLoading({
                    text: "Loading Response JSON ...",
                    position: "inside"
                });

            },
            complete: function() {
                l.stop();
                $("#ambiverse-annotated-text").isLoading("hide");
                $("#ambiverse-json-linking-loader").isLoading("hide");
            }
        });
    }

    function annotate_text(mentions) {

        var annotatedArray = [];
        var prevOffset = 0;
        mentions.forEach(function(value, key, mentions) {

            var mentionText = value["text"];
            var charLength = value["charLength"];
            var offset = value["charOffset"];
            var endIndex = offset + charLength;
            var entity = value["entity"];


            var type =  "Unknown";
            if(typeof entity !== 'undefined' && 'categories' in entity) {
                type = determine_type(entity["categories"]);
            }

            if (endIndex <= text.length) {
                annotatedArray.push(text.substring(prevOffset, offset));
                annotatedArray.push("<span class='mention  "+ typeColors[type] +"'");

                if(typeof entity !=='undefined' && 'id' in entity) {
                    annotatedArray.push(" data-id='" + entity.id+"'");
                }
                if(typeof entity !=='undefined' && 'confidence' in entity) {
                    annotatedArray.push("data-confidence='" + value["entity"].confidence +"'");
                }
                annotatedArray.push(">" + mentionText + "</span>");
                prevOffset = endIndex;
            }

        });
        if(prevOffset<=text.length) {
            var endIndex = text.length;
            annotatedArray.push(text.substring(prevOffset, endIndex));
        }

        return annotatedArray.join("").replace(/(?:\r\n|\r|\n)/g, '<br />');
    }

    function entity_view(entities) {
        var viewArray = [];
        renderedEntities = [];

        viewArray.push('<ul class="flex-container">');
        entities.forEach(function (value, key, entities) {

            if(!renderedEntities.contains(value.id)) {
                viewArray.push('<li class="flex-item">');
                viewArray.push(entity_box(value));
                viewArray.push('</li>');
                renderedEntities.push(value.id);
            }
        });
        viewArray.push('</div>');

        return viewArray.join('');
    }

    function entity_box(entity) {
        var type = determine_type(entity["categories"]);
        //console.log("entity="+entity.name+" type="+type+" color="+typeColors[type]);

        var viewArray = [];
        viewArray.push('<div class="media white-box entity-box ');
        viewArray.push(typeColors[type]);
        viewArray.push('" data-id="');
        viewArray.push(entity.id);
        viewArray.push('">');
        viewArray.push('<div class="ribbon ');
        viewArray.push(typeColors[type]);
        viewArray.push('">')
        viewArray.push(type);
        viewArray.push('</div>');
        viewArray.push('<div class="pull-left media-left">');
        viewArray.push('<img class="media-object"');
        viewArray.push('src="');
        viewArray.push(generate_thumbinail_image(entity.imageUrl, 100));
        viewArray.push('" alt="');
        viewArray.push(entity.name);
        viewArray.push('" onerror = "this.src=\'');
        viewArray.push(unknownImage);
        viewArray.push('\'"');
        // if(generate_thumbinail_image(entity.imageUrl, 200)===unknownImage) {
        //     viewArray.push(' style="width: 60px"');
        // }
        viewArray.push('>');
        viewArray.push('<div>&nbsp;</div>');
        if(typeof entity.links !=='undefined' && entity.links.length > 0 ) {
            //viewArray.push('</small>');
            viewArray.push('<div>');
            entity.links.forEach(function (value, key) {

                if(value.source==='Wikipedia') {
                    viewArray.push('<a href="');
                    viewArray.push(value.url);
                    viewArray.push('" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-wikipedia-w fa-1"></i>');
                    viewArray.push('</a>');
                }
            });
            viewArray.push('</div>');
        }

        viewArray.push('</div>');
        viewArray.push('<div class="media-body">');
        viewArray.push('<h4 class="media-heading">');
        viewArray.push(entity.name);
        viewArray.push('</h4>')
        if(typeof entity.description !=='undefined' && entity.description.length > 120) {
            //viewArray.push('<small>');
            viewArray.push(entity.description.substring(0, 120));
            viewArray.push(' ... ');
        }else {
            viewArray.push(entity.description);
        }



        if('confidence' in entity) {
            viewArray.push('<div>&nbsp;</div>');
            viewArray.push('<div><strong>Confidence:</strong> ');
            viewArray.push(parseFloat(Math.round(entity.confidence * 100) / 100).toFixed(2));
            viewArray.push('</div>');
        }

        viewArray.push('</div>');
        if(!('confidence' in entity)) {
            viewArray.push('<div style="position: absolute; bottom: 30px; margin-right: 30px; flex: 1 0 auto;"><em><small>We recognize the name but do not find a corresponding entity  in our knowledge graph (or we are not confident enough that it is correct).</small></em></div>');
        }
        viewArray.push('</div>');
        //viewArray.push('</div>');

        return viewArray.join("");
    }

    function determine_type(categories) {
        if(typeof categories !== 'undefined') {
            if (categories.contains("artist")) {
                return "Artist";
            }
            if (categories.contains("person")) {
                return "Person";
            }
            if (categories.contains("location")) {
                return "Location";
            }
            if (categories.contains("organization")) {
                return "Organization";
            }
            if (categories.contains("artifact")) {
                return "Artifact";
            }
            if (categories.includes("event")) {
                return "Event";
            }
        }else {
            return "Unknown";
        }
        return "Other";

    }


    String.prototype.replaceAll = function(search, replacement) {
        var target = this;
        return target.split(search).join(replacement);
    };

    Array.prototype.contains = function ( needle ) {
        var result = false;
        $(this).each(function (index, item) {
            if (item.includes(needle)) {
                result = true;
            }
        });
        return result;
    };



    function generate_thumbinail_image(imageUrl, widthInPixels) {
        if(typeof imageUrl !=='undefined') {
            var insertIndex = -1;
            var thumbnailUrl = imageUrl;

            if (imageUrl.includes("/commons")) {
                insertIndex = imageUrl.indexOf("/commons") + "/commons".length;
            } else if (imageUrl.includes("/en")) {
                insertIndex = imageUrl.indexOf("/en") + "/en".length;
            }

            if (insertIndex != -1) {
                thumbnailUrl = imageUrl.substring(0, insertIndex);
                thumbnailUrl += "/thumb";

                thumbnailUrl += imageUrl.substring(insertIndex, insertIndex + nthIndex(imageUrl.substring(insertIndex), "/", 3) + 1);
                thumbnailUrl += imageUrl.substring(insertIndex + "/thumb".length);
                // Add the last part twice
                var imageName = imageUrl.substring(imageUrl.lastIndexOf('/') + 1);
                if (imageName.endsWith(".svg")) {
                    imageName += ".png";
                }
                thumbnailUrl += "/" + widthInPixels + "px-" + imageName;
            }
            return thumbnailUrl;
        } else {
            return unknownImage;
        }
    }


    function nthIndex(str, pat, n){
        var L= str.length, i= -1;
        while(n-- && i++<L){
            i= str.indexOf(pat, i);
            if (i < 0) break;
        }
        return i;
    }

    function clone(obj) {
        var copy;

        // Handle the 3 simple types, and null or undefined
        if (null == obj || "object" != typeof obj) return obj;

        // Handle Date
        if (obj instanceof Date) {
            copy = new Date();
            copy.setTime(obj.getTime());
            return copy;
        }

        // Handle Array
        if (obj instanceof Array) {
            copy = [];
            for (var i = 0, len = obj.length; i < len; i++) {
                copy[i] = clone(obj[i]);
            }
            return copy;
        }

        // Handle Object
        if (obj instanceof Object) {
            copy = {};
            for (var attr in obj) {
                if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
            }
            return copy;
        }

        throw new Error("Unable to copy obj! Its type isn't supported.");
    }


    var updateState = _.throttle(function() {
        // clear then parameters first
        state = {};

        state['text'] = $('#ambiverse-text-input').val();
        state['language'] = $("#settings-language").val();
        state['confidenceThreshold'] = thresholdSlider.slider('getValue');
        state['analyze'] = "true";

        var st = $.param(state);
        history.pushState(null, null, '?' + st+'#entity-linking-demo');
    }, 500, true);

})( jQuery );
