(function ($) {
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
        Relation: "magenta",
        Concept: "gray",
    };


    var mentions = [];
    var mentionsWithMeta = [];
    var text = '';
    var entityMetadata = {};
    var renderedEntities = [];
    var thresholdSlider;
    var facts = [];

    var state = $.deparam.querystring();
    var requestInProgress = false;
    var version = "v1";


    var unknownImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22220%22%20height%3D%22220%22%20viewBox%3D%220%200%20220%20220%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M0%200h220v220H0z%22%2F%3E%3Cpath%20fill%3D%22%23ABABAB%22%20d%3D%22M98.17%20120.57l-.14-3.64c-.42-7.14%201.96-14.42%208.26-21.98%204.48-5.32%208.12-9.8%208.12-14.56%200-4.9-3.22-8.12-10.22-8.4-4.62%200-10.22%201.68-13.86%204.2l-4.76-15.26c5.04-2.94%2013.44-5.74%2023.38-5.74%2018.48%200%2026.88%2010.22%2026.88%2021.84%200%2010.64-6.58%2017.64-11.9%2023.52-5.18%205.74-7.28%2011.2-7.14%2017.5v2.52H98.17zm-3.64%2019.32c0-7.42%205.18-12.74%2012.46-12.74%207.56%200%2012.46%205.32%2012.6%2012.74%200%207.28-5.04%2012.74-12.6%2012.74-7.42%200-12.46-5.46-12.46-12.74z%22%2F%3E%3C%2Fsvg%3E";
    var defaultPersonImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22220%22%20height%3D%22220%22%20viewBox%3D%220%200%20220%20220%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M0%200h220v220H0z%22%2F%3E%3Cpath%20fill%3D%22%23ABABAB%22%20d%3D%22M139.266%20161.434H80.733c-10.648%200-17.88-6.496-17.88-17.346%200-15.135%203.548-38.374%2023.17-38.374%202.077%200%2010.85%209.31%2023.977%209.31%2013.126%200%2021.9-9.31%2023.976-9.31%2019.622%200%2023.172%2023.24%2023.172%2038.374%200%2010.85-7.234%2017.346-17.882%2017.346zM110%20110c-14.198%200-25.717-11.52-25.717-25.717S95.803%2058.566%20110%2058.566s25.716%2011.52%2025.716%2025.717S124.196%20110%20110%20110z%22%2F%3E%3C%2Fsvg%3E";
    var defaultArtistImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22220%22%20height%3D%22220%22%20viewBox%3D%220%200%20220%20220%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M0%200h220v220H0z%22%2F%3E%3Cg%20fill%3D%22%23ABABAB%22%3E%3Cpath%20d%3D%22M137.467%20134.266c-6.38%209.36-13.82%2018.633-20.546%2025.583-2.353%202.43-4.563%204.526-6.61%206.27%2020.077-.384%2038.94-11.333%2028.56-29.648-.463-.76-.932-1.493-1.403-2.206zM92.735%20143.698c0-4.627%203.75-8.38%208.38-8.38.606%200%201.198.065%201.77.19%203.252-6.067%207.013-12.313%2011.017-18.27%203.117-4.634%206.008-8.524%208.697-11.708-.46-4.684-.126-10.258%201.325-17.243%207.333-35.263-52.708-59.96-70.304-2.98-10.893%2035.192%203.353%2061.588%2035.193%2076.67%201.088.508%202.215.965%203.37%201.375.296-3.352%201.533-7.734%203.702-13.11-1.918-1.534-3.15-3.894-3.15-6.544zm-28.412-40.804c0-4.63%203.752-8.38%208.38-8.38%204.627%200%208.38%203.75%208.38%208.38%200%204.626-3.752%208.38-8.38%208.38-4.628%200-8.38-3.754-8.38-8.38zm15.503%2032.425c-4.63%200-8.38-3.753-8.38-8.38%200-4.63%203.75-8.382%208.38-8.382%204.627%200%208.38%203.752%208.38%208.38%200%204.63-3.753%208.38-8.38%208.38zm6.92-61.334c0-5.897%204.78-10.678%2010.677-10.678%205.896%200%2010.675%204.78%2010.675%2010.678%200%205.896-4.78%2010.676-10.675%2010.676-5.898%200-10.678-4.782-10.678-10.676z%22%2F%3E%3Cpath%20d%3D%22M138.632%20105.292h-.003c-2.1-1.347-3.814-3.073-5.092-5.032-4.184%202.88-9.495%208.714-16.482%2019.104-14.3%2021.257-24.865%2045.57-19.907%2048.818.373.243.825.362%201.346.362%206.392%200%2023.292-17.764%2036.51-37.418%207.255-10.782%2010.634-18.02%2011.557-22.96-1.436-.2-2.927-.582-4.478-1.155-1.214-.45-2.376-1.028-3.453-1.718zM169.938%2070.972c-.275-1.265-1.42-2.153-2.713-2.102-10.35.386-18.62%202.4-24.584%205.983-6.78%204.077-8.87%209.107-9.43%2012.61-.428%202.678-.077%205.35.907%207.784%201.63%204.036%205%207.41%209.458%209.056%202.336.862%204.44%201.283%206.438%201.283.303%200%20.603-.012.9-.03%202.268-.15%204.427-.868%206.434-2.14%205.977-3.792%205.474-10.022%205.03-15.52-.552-6.84-.558-11.17%205.972-13.88%201.196-.498%201.865-1.78%201.588-3.044z%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E";
    var defaultOrganizationImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22220%22%20height%3D%22220%22%20viewBox%3D%220%200%20220%20220%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M0%200h220v220H0z%22%2F%3E%3Cpath%20fill%3D%22%23ABABAB%22%20d%3D%22M174.292%2075.71v8.573h-8.572c0%202.344-2.076%204.286-4.62%204.286H58.9c-2.544%200-4.62-1.943-4.62-4.287h-8.572V75.71L110%2049.995l64.292%2025.717zm0%2085.724v8.572H45.708v-8.572c0-2.344%202.076-4.286%204.62-4.286h119.343c2.546%200%204.622%201.942%204.622%204.286zm-94.295-68.58v51.435h8.572V92.854h17.144v51.434h8.572V92.854h17.145v51.434h8.573V92.854h17.145v51.434h3.95c2.546%200%204.622%201.94%204.622%204.285v4.286H54.28v-4.285c0-2.344%202.076-4.286%204.62-4.286h3.952V92.854h17.145z%22%2F%3E%3C%2Fsvg%3E";
    var defaultArtifactImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22220%22%20height%3D%22220%22%20viewBox%3D%220%200%20220%20220%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M0%200h220v220H0z%22%2F%3E%3Cpath%20fill%3D%22%23ABABAB%22%20d%3D%22M69.282%20161.434h-4.286c-8.237%200-15.002-6.764-15.002-15.002v-55.72c0-8.237%206.765-15%2015.002-15h4.286v85.722zm75.007%200H75.71V75.71h8.573V64.997c0-3.55%202.88-6.43%206.43-6.43h38.575c3.55%200%206.43%202.88%206.43%206.43V75.71h8.57v85.724zM127.144%2075.71v-8.57h-34.29v8.57h34.29zm42.86%2070.722c0%208.238-6.763%2015.002-15%2015.002h-4.286V75.71h4.285c8.237%200%2015%206.765%2015%2015.003v55.72z%22%2F%3E%3C%2Fsvg%3E";
    var defaultEventImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22220%22%20height%3D%22220%22%20viewBox%3D%220%200%20220%20220%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M0%200h220v220H0z%22%2F%3E%3Cpath%20fill%3D%22%23ABABAB%22%20d%3D%22M165.72%20161.434c0%204.688-3.884%208.572-8.572%208.572H62.853c-4.688%200-8.572-3.885-8.572-8.572V75.71c0-4.687%203.886-8.57%208.573-8.57h8.572v-6.43c0-5.894%204.822-10.716%2010.716-10.716h4.287c5.894%200%2010.715%204.822%2010.715%2010.715v6.43h25.717v-6.43c0-5.894%204.82-10.716%2010.715-10.716h4.286c5.895%200%2010.716%204.822%2010.716%2010.715v6.43h8.572c4.688%200%208.572%203.884%208.572%208.57v85.724zm-8.573%200v-68.58H62.853v68.58h94.294zM88.57%2060.71c0-1.206-.94-2.144-2.144-2.144H82.14c-1.206%200-2.144.938-2.144%202.143v19.287c0%201.205.938%202.144%202.144%202.144h4.286c1.205%200%202.143-.937%202.143-2.143V60.71zm18.685%2088.133c-.87.804-2.21.804-3.08%200l-19.29-19.287c-.802-.87-.802-2.21%200-3.014l3.082-3.08c.804-.805%202.144-.805%203.014%200l14.734%2014.732%2029.735-29.734c.87-.804%202.21-.804%203.013%200l3.08%203.08c.804.804.804%202.144%200%203.014l-34.288%2034.29zm32.748-88.134c0-1.206-.938-2.144-2.143-2.144h-4.286c-1.206%200-2.144.938-2.144%202.143v19.287c0%201.205.938%202.144%202.144%202.144h4.286c1.205%200%202.143-.937%202.143-2.143V60.71z%22%2F%3E%3C%2Fsvg%3E";
    var defaultLocationImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22220%22%20height%3D%22220%22%20viewBox%3D%220%200%20220%20220%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M0%200h220v220H0z%22%2F%3E%3Cpath%20fill%3D%22%23ABABAB%22%20d%3D%22M142.08%20104.843l-24.378%2051.836c-1.406%202.945-4.487%204.754-7.702%204.754s-6.295-1.81-7.635-4.755L77.92%20104.842c-1.74-3.683-2.21-7.902-2.21-11.987%200-18.953%2015.338-34.29%2034.29-34.29%2018.953%200%2034.29%2015.337%2034.29%2034.29%200%204.084-.47%208.304-2.21%2011.987zM110%2075.71c-9.442%200-17.145%207.702-17.145%2017.146S100.558%20110%20110%20110c9.443%200%2017.145-7.7%2017.145-17.145S119.443%2075.71%20110%2075.71z%22%2F%3E%3C%2Fsvg%3E";
    var defaultOtherImage = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22220%22%20height%3D%22220%22%20viewBox%3D%220%200%20220%20220%22%3E%3Cpath%20fill%3D%22%23EEE%22%20d%3D%22M0%200h220v220H0z%22%2F%3E%3Cpath%20fill%3D%22%23ABABAB%22%20d%3D%22M156.68%20132.033l-4.288%207.367c-2.344%204.085-7.635%205.49-11.72%203.147L122.86%20132.3v20.56c0%204.69-3.886%208.573-8.573%208.573h-8.572c-4.688%200-8.572-3.885-8.572-8.572V132.3L79.33%20142.548c-4.086%202.344-9.377.938-11.72-3.147l-4.287-7.367c-2.344-4.085-.938-9.376%203.147-11.72L84.283%20110%2066.47%2099.687c-4.086-2.344-5.492-7.635-3.148-11.72l4.286-7.367c2.344-4.085%207.635-5.49%2011.72-3.147L97.142%2087.7V67.14c0-4.69%203.884-8.573%208.572-8.573h8.572c4.688%200%208.572%203.885%208.572%208.572V87.7l17.814-10.247c4.084-2.344%209.375-.938%2011.72%203.147l4.286%207.367c2.344%204.085.938%209.376-3.148%2011.72L135.716%20110l17.814%2010.313c4.086%202.344%205.492%207.635%203.15%2011.72z%22%2F%3E%3C%2Fsvg%3E";

    $(document).ready(function () {

        $('body').tooltip({
            selector: '[rel=tooltip]',
            container: 'body',
            html: 'true'
        });
    });
    $(function () {



        $('#ambiverse-text-input').autogrow({vertical: true, horizontal: false});

        $('#ambiverse-text-input').bind('input propertychange change keyup', function () {
            updateState();
        });


        $("#analyze").click(function () {

            analyze_text();

            //Log the event in Google Analytics
            //ga('send', 'event', 'Buttons', 'clicked', 'Analyze Button');
        });

        $("#settings-language").change(function () {
                updateState();
                delayRequest();
        });

        // With JQuery
        thresholdSlider = $('#settings-threshold').slider({
            formatter: function (value) {
                return 'Current value: ' + value;
            }
        });


        $(thresholdSlider).change(function (e) {
            var val = e.value.newValue;
                $("#threshold-val").text(val);
                updateState();
                delayRequest();
        });

        var timer;
        function delayRequest() {

            clearTimeout(timer);
            timer = setTimeout(function() {
                if(requestInProgress === false) {
                    analyze_text();
                }
            }, 500);
        }

        $(document).on('mousedown', 'span.mention', function (e) {
            var mention = $(this);
            var boxToSelect = null;
            var id = $(this).data("id");

            select_mention_and_box(mention, boxToSelect, id);
        });

        $(document).on('mousedown', '.entity-box', function (e) {
            var mention = null;
            var boxToSelect = $(this);
            var id = $(this).data("id");

            select_mention_and_box(mention, boxToSelect, id);
        });

        if (state.eldText && state.eldText !== "") {
            $('#ambiverse-text-input').val(state.eldText);
        }

        if (state.language && state.language !== "") {
            $('#settings-language').val(state.language);
        }

        if (state.confidenceThreshold && state.confidenceThreshold !== "") {
            thresholdSlider.slider('setValue', parseFloat(state.confidenceThreshold));
            $("#threshold-val").text(state.confidenceThreshold);
        }

        if (state.analyze && state.analyze === "true") {
            analyze_text();
        }


    });

    function select_mention_and_box(mention, box, id) {

        var mentions = [];
        var boxes = [];

        if (mention === null) {
            $("#ambiverse-annotated-text span.mention").each(function () {
                var idTmp = $(this).data("id");
                if (idTmp === id) {
                    mentions.push(this);
                }
            });
        } else {
            mentions.push(mention);
        }

        if (box === null) {
            $("#ambiverse-result-entities .entity-box").each(function () {
                var idTmp = $(this).data("id");
                if (idTmp === id) {
                    boxes.push(this);
                }
            });
        } else {
            boxes.push(box);
        }
        if (box === null) {
            $("#ambiverse-result-open-facts .mention-box").each(function () {
                var idTmp = $(this).data("id");
                if (idTmp === id) {
                    boxes.push(this);
                }
            });
        } else {
            boxes.push(box);
        }



        var topMention;
        mentions.forEach(function (value, key) {
            $(value).addClass("selected");

            if (typeof topMention === 'undefined' || topMention.offset().top > $(value).offset().top) {
                topMention = $(value);
            }
        });
        if (typeof topMention !== 'undefined' && box !== null) {
            scrollToElement(topMention);
        }

        var bottomBox;
        boxes.forEach(function (value, key) {
            $(value).addClass("selected");

            if (typeof bottomBox === 'undefined' || bottomBox.offset().top < $(value).offset().top) {
                bottomBox = $(value);
            }
        });

        if (typeof bottomBox !== 'undefined' && mention !== null) {
            scrollToElement(bottomBox);
        }


        $("span.mention").bind("mouseup touchend", function (e) {
            var mention = $(this);
            e.stopPropagation();
            $("span.mention").unbind('mouseup touchend');
        });

        $("#ambiverse-annotated-text:not(span.mention)").bind("mousedown touchstart", function (e) {

            mentions.forEach(function (value, key) {
                $(value).removeClass("selected");
            });

            boxes.forEach(function (value, key) {
                $(value).removeClass("selected");
            });

            $("#ambiverse-annotated-text:not(span.mention)").bind("mouseup touchend", function (e) {
                $("#ambiverse-annotated-text:not(span.mention)").unbind('mouseup touchend');
            });
        });

        $("#ambiverse-result-entities .entity-box").bind("mouseup touchend", function (e) {
            var mention = $(this);
            e.stopPropagation();
            $("#ambiverse-result-entities .entity-box").unbind('mouseup touchend');
        });

        $("#ambiverse-result-entities:not(.entity-box)").bind("mousedown touchstart", function (e) {

            mentions.forEach(function (value, key) {
                $(value).removeClass("selected");
            });

            boxes.forEach(function (value, key) {
                $(value).removeClass("selected");
            });

            $("#ambiverse-result-entities:not(.entity-box)").bind("mouseup touchend", function (e) {
                $("#ambiverse-result-entities:not(.entity-box)").unbind('mouseup touchend');
            });
        });
    }


    function get_entity_metadata(entities, language) {

        var entityIds = [];


        entities.forEach(function (value, key, mentions) {
            if (!jQuery.isEmptyObject(value)) {
                entityIds.push(value.id);
            }
        });

        //console.log(entities);
        //console.log(entityIds);
        $.ajax({
            type: "post",
            dataType: "json",
            url: ajax_obj.ajax_url,
            data: {
                action: "tag_entity_metadata",
                entities: entityIds,
                _ajax_nonce: ajax_obj.nonce
            },
            success: function (data) {
                //console.log(data);
                $("#ambiverse-json-output-meta code").text(JSON.stringify(data, null, 2));
                $('#ambiverse-json-output-meta code').each(function (i, block) {
                    hljs.highlightBlock(block);
                });


                if (version === "v1" && typeof data !== 'undefined' && data !== null) {
                    var entitiesTmp = data["entities"];
                    if (typeof entitiesTmp !== 'undefined' && entitiesTmp !== 'null' && entitiesTmp.length > 0) {
                        //hash the entity metadata
                        entitiesTmp.forEach(function (value, key) {
                            entityMetadata[value.id] = value;
                        });
                    }
                }
                if(version === "v2") {

                    entityMetadata = data["entities"];
                }


                var allEntities = [];
                var entitiesWithconfidences = {};

                var mentionsCopy = clone(mentions);
                mentionsCopy.forEach(function (mention, key) {

                    if (!jQuery.isEmptyObject(mention["entity"])) {

                        var confidence = 0;
                        if(version === "v1") {
                            var confidence = mention["entity"].confidence;

                            if (mention["entity"].id in entitiesWithconfidences) {
                                confidence = Math.max(confidence, entitiesWithconfidences[mention["entity"].id]);
                            }
                        } else {
                            entities.forEach(function(entity, key) {
                                if(entity.id === mention["entity"].id) {
                                    confidence = entity.salience;
                                }
                            });
                        }

                        if (typeof entityMetadata[mention["entity"].id] !== 'undefined') {
                            mention["entity"] = entityMetadata[mention["entity"].id];
                        }

                        mention["entity"]["confidence"] = confidence;
                        entitiesWithconfidences[mention["entity"].id] = confidence;
                        allEntities.push(entityMetadata[mention["entity"].id]);


                    } else {
                        var entity = {};
                        entity["id"] = mention["text"];
                        entity["name"] = null;
                        entity["names"] = {};
                        entity["type"] = "UNKNOWN";
                        if(version === "v1") {
                            //entity["confidence"] = 0;
                            entity["name"] = mention.text;
                        } else {

                            var nameByLanguage = {};
                            nameByLanguage.language = language;
                            nameByLanguage.value = mention.text;
                            entity["names"][language] = nameByLanguage;
                        }
                        allEntities.push(entity);
                        mention["entity"] = entity;
                    }

                });


                 var objects = [];
                 mentionsCopy.forEach(function (value, key, mentionsCopy) {
                    objects.push(value);
                 });

                 splitRelationWords(facts).forEach(function(value, key) {
                    objects.push(value);
                 });

                 //Sort the objects to be shown by the offset
                 objects.sort(function(a,b) {return (a.charOffset > b.charOffset) ? 1 : ((b.charOffset > a.charOffset) ? -1 : 0);} );

                $("#ambiverse-annotated-text").html(annotate_text(objects));

                $("#ambiverse-result-entities").html(entity_view(allEntities, language));

                 if(typeof facts !== 'undefined' && facts !== null) {
                    show_open_facts(facts, language);
                 }

            },
            error: function (xhr, textStatus, errorThrown) {

            },
            beforeSend: function () {
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
            complete: function () {
                $("#ambiverse-result-entities-loader").isLoading("hide");
                $("#ambiverse-json-meta-loader").isLoading("hide");

                resizeListItems();
            }
        })
    }

    function splitRelationWords(facts) {
        var annotatedOffsets = [];
        var objects = [];
        facts.forEach(function(value, key, facts) {
            if(!annotatedOffsets.includes(value.relation.charOffset)) {
                annotatedOffsets.push(value.relation.charOffset);
                var snippet = text.substring(value.relation.charOffset, value.relation.charOffset+value.relation.charLength);
                var relationWords = value.relation.text.split(" ");
                relationWords.forEach(function(word, key2) {
                    var index = snippet.search("\\b"+word+"\\b");
                    var relationMention = {};
                    relationMention.type = "Relation";
                    relationMention.id = relationWords[0]+"-"+key;
                    relationMention.text = word;
                    relationMention.charOffset = value.relation.charOffset+index;
                    relationMention.charLength = word.length;
                    objects.push(relationMention);
                });
            }
        });

        //Connect consecutive words in a relation
        var len = objects.length;
        while(len-- && len >0) {

            if(objects[len-1].charOffset+objects[len-1].charLength+1 === objects[len].charOffset) {
                objects[len-1].text = objects[len-1].text + " "+objects[len].text;
                objects[len-1].charLength = objects[len-1].charLength + objects[len].charLength + 1;
                objects.splice(len,1);
            }


        }

        return objects;
    }
    function analyze_text() {
        var l = Ladda.create(document.querySelector('.progress-button'));

        var textInput = $("#ambiverse-text-input");
        var textInputString = $(textInput).val();

        //Hardcoding "Page" to be annotated manually
        if(textInputString.startsWith("Page")) {
            textInputString = "[[Page]]"+textInputString.substr(4);
        }

        var annotatedMentions = [];
        var regex = /\[\[(.*?)\]\]/g;
        var m;

        var mentionsFound = 0;
        while ((m = regex.exec(textInputString)) !== null) {
            // This is necessary to avoid infinite loops with zero-width matches
            if (m.index === regex.lastIndex) {
                regex.lastIndex++;
            }
            var charOffset = m["index"] - mentionsFound * 4;
            var charLength = m[1].length;
            annotatedMentions.push({
                "charOffset": charOffset,
                "charLength": charLength
            });
            mentionsFound++;
        }

        textInputString = $.trim(textInputString.replaceAll("[[", "").replaceAll("]]", ""));

        if (textInputString.charAt(textInputString.length-1) !== '.' && textInputString.charAt(textInputString.length-1) !== '?' && textInputString.charAt(textInputString.length-1) !== '!') {
            textInputString += ".";
        }

        var coherentDocument = $(textInput).data("coherent-document"); //$("#settings-coherent").prop("checked");
        var extractConcepts = $(textInput).data("concept");
        var confidenceThreshold = thresholdSlider.slider('getValue');
        var language = $("#settings-language").val();
        var apiEndpoint = $("#api-endpoint").val();
        var apiMethod = $("#api-method").val();

        var data = {
            action: "tag_analyze_document",
            annotatedMentions: annotatedMentions,
            text: textInputString,
            coherentDocument: coherentDocument,
            extractConcepts: extractConcepts,
            confidenceThreshold: confidenceThreshold,
            language: language,
            apiEndpoint: apiEndpoint,
            apiMethod: apiMethod,
            _ajax_nonce: ajax_obj.nonce
        };

        //console.log(data);

        $.ajax({
            type: "post",
            dataType: "json",
            url: ajax_obj.ajax_url,
            data: data,
            success: function (data) {
                //console.log(data);
                version = ajax_obj.version;


                if (typeof data["code"] !== 'undefined' && data["code"] !== 200) {
                    $("#ambiverse-annotated-text").removeClass("well");
                    $("#ambiverse-annotated-text").addClass("alert alert-danger");

                    
                    if(typeof data["message"] !== 'undefined' && data["message"] != null && data["message"].startsWith("Language could not be detected.")) {
                        $("#ambiverse-annotated-text").html("The input language could not be detected. Please try selecting it from the dropdown box.");
                    }else if (data["message"] != null) {
                        $("#ambiverse-annotated-text").html(data["message"]);
                    }else {
                        $("#ambiverse-annotated-text").html("An error has occurred. Please check your input!");
                    }
                    $("#ambiverse-json-output code").text(JSON.stringify(data, null, 2));
                    $('#ambiverse-json-output code').each(function (i, block) {
                        hljs.highlightBlock(block);
                    });
                } else {
                    text = textInputString;

                    var allEntities = [];
                    mentions = data["matches"];
                    facts = data["facts"];

                    if(version === "v2") {
                        allEntities = data["entities"];
                    }
                    var language = data["language"];

                    if (version === "v1" && typeof mentions !== 'undefined') {
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

                    if (mentions.length === 0) {
                        $("#ambiverse-result-entities").html("<div style='margin: 5px 5px 20px 5px;' class='alert alert-warning'>No matches found!</div>");
                    } else {
                        //Get entity metadata for all entities in the text
                        get_entity_metadata(allEntities, language);
                    }


                }
            },
            error: function (xhr, textStatus, errorThrown) {

            },
            beforeSend: function () {
                requestInProgress = true;
                if ($("#ambiverse-annotated-text").hasClass("alert")) {
                    $("#ambiverse-annotated-text").removeClass("alert");
                    $("#ambiverse-annotated-text").removeClass("alert-danger");
                    $("#ambiverse-annotated-text").addClass("well");
                }

                $("#ambiverse-json-output code").text("");
                $("#ambiverse-json-output-meta code").text("");
                $("#ambiverse-annotated-text").html("");
                $("#ambiverse-result-entities").html("");
                $("#ambiverse-result-open-facts").html("");
                $("#open-facts-title").hide();

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
            complete: function () {
                requestInProgress = false;
                l.stop();
                $("#ambiverse-annotated-text").isLoading("hide");
                $("#ambiverse-json-linking-loader").isLoading("hide");
            }
        });
    }

    function annotate_text(objects) {


        var prevOffset = 0;
        var annotatedText = text;
        var annotatedArray = [];



        objects.forEach(function (value, key, objects) {
            var mentionText = value["text"];
            var charLength = value["charLength"];
            var offset = value["charOffset"];
            var endIndex = offset + charLength;
            var entity = value["entity"];

            var type = "Unknown";
            if (typeof entity !== 'undefined' && 'categories' in entity) {

                if(version == "v1") {
                    type = determine_type(entity["categories"]);
                } else {
                    type = upperCaseFirstLetter(lowerCaseAllWordsExceptFirstLetters(entity["type"]));
                }
            } else {
                type = value["type"];
            }

            if (endIndex <= text.length) {

                annotatedArray.push(text.substring(prevOffset, offset));
                annotatedArray.push("<span class='mention  " + typeColors[type] + "' rel='tooltip' data-toggle='tooltip' data-placement='top' title='Click here to get more info.'");

                if (typeof entity !== 'undefined' && 'id' in entity) {
                    var entityId = entity.id.replace(/'/g, "&#039;");
                    annotatedArray.push(" data-id='" + entityId + "'");
                } else {
                    var entityId = value.id;
                    annotatedArray.push(" data-id='" + entityId + "'");
                }
                if (typeof entity !== 'undefined' && 'confidence' in entity) {
                    annotatedArray.push("data-confidence='" + value["entity"].confidence + "'");
                }
                if(value.type === "Relation") {
                    mentionText = text.substring(offset, endIndex);
                    //console.log(mentionText);
                }
                annotatedArray.push(">" + mentionText + "</span>");
                prevOffset = endIndex;
            }
            //annotatedText = annotatedText.replace(text.substring(offset, endIndex), annotatedArray.join("").replace(/(?:\r\n|\r|\n)/g, '<br />'));

        });

//        if(typeof facts !== 'undefined' && facts !== null) {
//
//            var annotatedOffsets = [];
//            facts.forEach(function (value, key, facts) {
//                var relationAnnotation = [];
//                var relation = value["relation"];
//                console.log(relation)
//
//                if(typeof relation !== 'undefined' && relation.charLength > 0 && relation.charOffset + relation.charLength <= text.length) {
//                    if(!annotatedOffsets.includes(relation.charOffset)) {
//                        annotatedOffsets.push(relation.charOffset);
//
//                        relationAnnotation.push("<span class='mention magenta' data-id='");
//                        relationAnnotation.push(relation.text.replaceAll(" ", "_")+"-"+key);
//                        relationAnnotation.push("'>")
//                        relationAnnotation.push(text.substring(relation.charOffset, relation.charOffset+relation.charLength));
//                        relationAnnotation.push("</span>");
//                        console.log("AAA "+relationAnnotation.join(""))
//                        annotatedText = annotatedText.replace(text.substring(relation.charOffset, relation.charOffset+relation.charLength), relationAnnotation.join("").replace(/(?:\r\n|\r|\n)/g, '<br />'));
//                        console.log(annotatedText);
//
//                        //console.log(relationAnnotation.join("").replace(/(?:\r\n|\r|\n)/g, '<br />'))
//                    }
//                }
//            });
//        }
        if (prevOffset <= text.length) {
            var endIndex = text.length;
            annotatedArray.push(text.substring(prevOffset, endIndex));
        }
        return annotatedArray.join("").replace(/(?:\r\n|\r|\n)/g, '<br />');
    }

    function show_open_facts(facts, language) {

        $("#open-facts-title").show();
        var viewArray = [];
        viewArray.push('<table class="table table-striped">');
        viewArray.push('<thead><tr>');
        viewArray.push('<td scope="col" style="min-width: 200px"><strong>Subject</strong></td>');
        viewArray.push('<td scope="col" style="min-width: 150px"><strong>Relation</strong></td>');
        viewArray.push('<td scope="col"><strong>Object</strong></td>');
        viewArray.push('</tr></thead>');
        viewArray.push('<tbody>');
        facts.forEach(function (value, key, facts) {

            var relationWords = value.relation.text.split(" ");
            viewArray.push('<tr data-toggle="collapse" data-target=".accordion-'+key+'" class="clickable-row"><td>');
            viewArray.push(value.subject.text);
            viewArray.push('</td><td class="mention-box" data-id="'+relationWords[0]+'-'+key+'">');
            viewArray.push(value.relation.text);
            viewArray.push('</td><td>');
            viewArray.push(value.object.text);
            viewArray.push('</td></tr>');

            viewArray.push('<tr class="hiddenRow collapse accordion-'+key+'">');
            viewArray.push('<td>');
            if(typeof value.subject.entities !== 'undefined' && value.subject.entities.length > 0) {
                viewArray.push('<strong>Entities</strong><ul class="list-group">');

                value.subject.entities.forEach(function (e, k, entities) {
                    var entity = entityMetadata[e.id];
                    var type  = upperCaseFirstLetter(lowerCaseAllWordsExceptFirstLetters(entity["type"]));
                    viewArray.push("<li class='list-group-item' style='border: none !important;'><span class='mention  " + typeColors[type] + "' rel='tooltip' data-toggle='tooltip' data-placement='top' title='Click here to get more info.'");
                    if (typeof entity !== 'undefined' && 'id' in entity) {
                        var entityId = entity.id.replace(/'/g, "&#039;");
                        viewArray.push(" data-id='" + entityId + "'>");
                     }
                     viewArray.push(entityMetadata[e.id].names[language].value);
                     viewArray.push("</span></li>");

                });
                viewArray.push('</ul>');
            } else {
                 viewArray.push("&nbsp;");
            }
            viewArray.push('</td>');
            viewArray.push('<td><strong>Normalized form</strong> <br />'+value.relation.normalizedForm+'</td>');
            viewArray.push('<td>');
            if(typeof value.object.entities !== 'undefined' && value.object.entities.length > 0) {
               viewArray.push('<strong>Entities</strong><ul>');

               value.object.entities.forEach(function (e, k, entities) {
                     var entity = entityMetadata[e.id];
                     if(typeof entity !== 'undefined') {
                         var type = "UNKNOWN";
                         if(typeof entity["type"] !== 'undefined') {
                            var type  = upperCaseFirstLetter(lowerCaseAllWordsExceptFirstLetters(entity["type"]));
                         }

                         viewArray.push("<li class='list-group-item' style='border: none !important;'><span class='mention  " + typeColors[type] + "' rel='tooltip' data-toggle='tooltip' data-placement='top' title='Click here to get more info.'");
                         if (typeof entity !== 'undefined' && 'id' in entity) {
                            var entityId = entity.id.replace(/'/g, "&#039;");
                            viewArray.push(" data-id='" + entityId + "'>");
                         }
                         if(typeof entityMetadata[e.id].names !== 'undefined') {
                            viewArray.push(entityMetadata[e.id].names[language].value);
                         }
                         viewArray.push("</span></li>");
                     }
               });
               viewArray.push('</ul>');
            } else {
               viewArray.push("&nbsp;");
            }
            viewArray.push('</td>');
            viewArray.push('</tr>');

        });

        $("#ambiverse-result-open-facts").html(viewArray.join(''));
    }

    function entity_view(entities, language) {
        var viewArray = [];
        renderedEntities = [];

        var entityLayout = $("#ambiverse-text-input").data("entity-layout");

        viewArray.push('<ul class="flex-container">');


        entities.forEach(function (value, key, entities) {
            if(value != undefined) {
                if(jQuery.inArray(value.id, renderedEntities) === -1 ) {
                    if (entityLayout === "layout1") {
                        viewArray.push('<li class="flex-item">');
                        viewArray.push(entity_box1(value, language));
                        viewArray.push('</li>');
                        renderedEntities.push(value.id);
                    } else if (entityLayout === "layout2") {
                        viewArray.push('<li class="list__item">');
                        viewArray.push(entity_box2(value, language));
                        viewArray.push('</li>');
                        renderedEntities.push(value.id);
                    }
               }
            }
        });
        viewArray.push('</div>');

        return viewArray.join('');
    }

     function getEntityMetaForLanguage(entity, language) {

          var result = {};

          if(typeof entity.names !== 'undefined' && typeof entity.names[language] !== 'undefined') {
           result.name = entity.names[language].value;
          } else if(typeof entity.names !== 'undefined' && typeof entity.names["en"] !== 'undefined') {
            result.name = entity.names["en"].value;
          }

          if(typeof entity.descriptions !== 'undefined' && typeof entity.descriptions[language] !== 'undefined') {
            result.shortDescription = entity.descriptions[language].value;
          }

          if(typeof entity.detailedDescriptions !== 'undefined' &&  typeof entity.detailedDescriptions[language] !== 'undefined') {
            result.description = entity.detailedDescriptions[language].value;
          }

           if(typeof entity.links !== 'undefined' && typeof entity.links[language] !== 'undefined') {
            result.link = entity.links[language].value
           } else if(typeof entity.links !== 'undefined' && typeof entity.links["en"] !== 'undefined') {
            result.link = entity.links["en"].value
           }


          return result;
        }

    function entity_box1(entity, language) {


        var displayEntity = entity;
        if(version === "v2") {
            displayEntity = getEntityMetaForLanguage(entity, language);
        }
        var type;
        if(version === "v1") {
            type = determine_type(entity["categories"]);
        } else {
            type = upperCaseFirstLetter(lowerCaseAllWordsExceptFirstLetters(entity["type"]));
        }


        var errorImage = getDefaultImageByType(type);

        var includeImages = $("#ambiverse-text-input").data("entity-images");
        var includeIcons = $("#ambiverse-text-input").data("entity-icons");
        var onlyFreeImages = $("#ambiverse-text-input").data("entity-free-images");

        var entityThumbnail = generate_thumbinail_image(entity.imageUrl, 280, type);

        if (onlyFreeImages === 1 && !isFreeImage(entityThumbnail)) {
            entityThumbnail = errorImage;
        }

        if (includeImages !== 1 && includeIcons === 1) {
            entityThumbnail = errorImage;
        }


        var viewArray = [];
        viewArray.push('<div class="media white-box entity-box mention-box ' + typeColors + '" data-id="' + entity.id + '">');

        viewArray.push('<div class="ribbon ' + typeColors[type] + '">' + type + '</div>');
        viewArray.push('<div class="pull-left media-left">');
        if (includeImages === 1 || includeIcons === 1) {
            viewArray.push('<img class="media-object" src="' + entityThumbnail + '" alt="' + displayEntity.name + '" onerror = "this.src=\'' + errorImage + '\'"' + '>');
        } else {
            viewArray.push('<div>&nbsp;</div>');
        }

        viewArray.push('<div>&nbsp;</div>');
        if(version === "v1") {
        if (typeof displayEntity.links !== 'undefined' && displayEntity.links.length > 0) {
            //viewArray.push('</small>');
            viewArray.push('<div>');
            displayEntity.links.forEach(function (value, key) {

                if (value.source === 'Wikipedia') {
                    viewArray.push('<a href="' + value.url + '" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-wikipedia-w fa-1"></i></a>');
                }
            });
            viewArray.push('</div>');
        }
        }else {
         viewArray.push('<div>');
         viewArray.push('<a href="');
         viewArray.push(displayEntity.link);
         viewArray.push('" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-wikipedia-w fa-1"></i>');
         viewArray.push('</a>');
         viewArray.push('</div>');
        }

        viewArray.push('</div>');
        viewArray.push('<div class="media-body">');
        viewArray.push('<h4 class="media-heading">' + displayEntity.name + '</h4>');

        if(typeof displayEntity.shortDescription !== 'undefined') {
            viewArray.push('<p><em>' + displayEntity.shortDescription + '</em></p>');
        }


        if (typeof displayEntity.description  !== 'undefined' && displayEntity.description.length > 120) {
            //viewArray.push(entity.description.substring(0, 120));
            var descArray =displayEntity.description.split(" ", 20);
            viewArray.push(descArray.join(" "));
            viewArray.push(' ... ');
        } else {
            viewArray.push(displayEntity.description );
        }

        if ('confidence' in entity) {
            viewArray.push('<div>&nbsp;</div>');
            if(version === "v1") {
                viewArray.push('<div class="confidence"><strong>Confidence:</strong> ');
            } else {
                viewArray.push('<div class="confidence"><strong>Salience:</strong> ');
            }
            viewArray.push(parseFloat(Math.round(entity.confidence * 100) / 100).toFixed(2));
            viewArray.push('</div>');
        }

        viewArray.push('</div>');
        if (!('confidence' in entity)) {
            viewArray.push('<div style="position: absolute; bottom: 30px; margin-right: 30px; flex: 1 0 auto;"><em><small>We recognize the name but do not find a corresponding entity  in our knowledge graph (or we are not confident enough that it is correct).</small></em></div>');
        }
        viewArray.push('</div>');

        return viewArray.join("");
    }

    function entity_box2(entity, language) {


        var displayEntity = entity;
        if(version === "v2") {
            displayEntity = getEntityMetaForLanguage(entity, language);
        }
       var type;
       if(version === "v1") {
            type = determine_type(entity["categories"]);
       } else {
            type = upperCaseFirstLetter(lowerCaseAllWordsExceptFirstLetters(entity["type"]));
       }


        var errorImage = getDefaultImageByType(type);

        var includeImages = $("#ambiverse-text-input").data("entity-images");
        var includeIcons = $("#ambiverse-text-input").data("entity-icons");
        var onlyFreeImages = $("#ambiverse-text-input").data("entity-free-images");
        var entityThumbnail = errorImage;

        if(typeof entity.image !== 'undefined') {
            entityThumbnail = generate_thumbinail_image(entity.image.url, 280, type);
        }

        if (onlyFreeImages === 1 && !isFreeImage(entityThumbnail)) {
            entityThumbnail = errorImage;
        }

        if (includeImages !== 1 && includeIcons === 1) {
            entityThumbnail = errorImage;
        }

        var viewArray = [];

        viewArray.push('<figure class="white-box entity-box mention-box' + typeColors[type] + ' list__item__inner" data-id="' + entity.id + '" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Click here to see the entity mentioned in the text.">');
        viewArray.push('<div class="ribbon ' + typeColors[type] + '">' + type + '</div>');

        if (includeImages === 1 || includeIcons === 1) {
            viewArray.push('<div class="crop"><img src="' + entityThumbnail + '" alt="' + displayEntity.name + '" onerror = "this.src=\'' + errorImage + '\'">');
            if(typeof entity.image !== 'undefined' && ((typeof entity.image.author !== 'undefined' && entity.image.author !== null) || (typeof entity.image.licenses !== 'undefined' && entity.image.licenses.length > 0))) {

                viewArray.push('<div class="image-attr">');
                if(typeof entity.image.author !== 'undefined' && entity.image.author !== null) {
                    viewArray.push('By <a target="_blank"');
                    if(typeof entity.image.author.url !== 'undefined' && entity.image.author.url != null) {
                        viewArray.push(' href="'+entity.image.author.url+'"');
                    }
                    viewArray.push('>'+entity.image.author.name+'</a>');
                }


                if(typeof entity.image.licenses !== 'undefined' && entity.image.licenses.length > 0) {
                    if(typeof entity.image.author !== 'undefined' && entity.image.author !== null) {
                        viewArray.push(', ');
                    }

                    entity.image.licenses.sort(function(a,b) {return (a.name > b.name) ? 1 : ((b.name > a.name) ? -1 : 0);} );
                    viewArray.push('<a target="_blank" href="'+entity.image.licenses[0].url+'">'+shortenLicenceName(entity.image.licenses[0].name)+'</a>');
                }
                viewArray.push('</div>');
            }
            viewArray.push('</div>');
        } else {
            viewArray.push('<div>&nbsp;</div>');
        }


        viewArray.push('<figcaption>');
        viewArray.push('<h3 class="media-heading">' + displayEntity.name + '</h3>');

        if(typeof displayEntity.shortDescription !== 'undefined') {
            viewArray.push('<p><em>' + displayEntity.shortDescription + '</em></p>');
        }

        if (typeof displayEntity.description !== 'undefined') {
            var descArray = displayEntity.description.split(" ");

            if (descArray.length > 15) {
                //viewArray.push(entity.description.substring(0, 120));
                //descArray = displayEntity.description.split(" ", 15);
                //viewArray.push(descArray.join(" "));
                const regex = /(^.*?[a-z]{2,}[.!?])\s+\W*[A-Z]/;
                let m;
                var entityDesc;
                if ((m = regex.exec(displayEntity.description)) !== null) {
                    // The result can be accessed through the `m`-variable.
                    m.forEach((match, groupIndex) => {
                        if(groupIndex === 1) {
                            entityDesc = match
                        }
                    });
                }
                viewArray.push(entityDesc) ;
                //viewArray.push(' ... ');
            } else {
                viewArray.push(displayEntity.description);
            }
        }

        if(version === "v2") {
          viewArray.push('<div>');
          viewArray.push(' <a href="');
          viewArray.push(displayEntity.link);
          viewArray.push('" target="_blank" >');
          //viewArray.push('<i class="fa fa-wikipedia-w fa-1"></i>');
          viewArray.push("Wikipedia")
          viewArray.push('</a>');
          viewArray.push('</div>');
        } else {
           if (typeof displayEntity.links !== 'undefined' && displayEntity.links.length > 0) {

            viewArray.push('<div>');
            displayEntity.links.forEach(function (value, key) {

                if (value.source === 'Wikipedia') {
                    viewArray.push('<a href="');
                    viewArray.push(value.url);
                    viewArray.push('" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-wikipedia-w fa-1"></i>');
                    viewArray.push('</a>');
                }
            });
            viewArray.push('</div>');
           }
        }

        if ('confidence' in entity) {
            viewArray.push('<div>&nbsp;</div>');
            viewArray.push('<div>&nbsp;</div>');
            if(version === "v1") {
                viewArray.push('<div class="confidence"><strong>Confidence:</strong> ');
            } else {
                viewArray.push('<div class="confidence"><strong>Salience:</strong> ');
            }
            viewArray.push(parseFloat(Math.round(entity.confidence * 100) / 100).toFixed(2) + '</div>');
        }

        viewArray.push('</div>');
        if (!('confidence' in entity)) {
            viewArray.push('<div>&nbsp;</div>');

            viewArray.push('<div class="unknown"><em><small>We recognize the name but do not find a corresponding entity  in our knowledge graph (or we are not confident enough that it is correct).</small></em></div>');
        }
        viewArray.push('</figcaption>');
        viewArray.push('</figure>');

        return viewArray.join("");
    }

    function shortenLicenceName(licence) {

        var result = licence;
        if(licence.startsWith("CreativeCommonsLicense")) {
            result = licence.replace("CreativeCommonsLicense-", "CC ").replaceAll("-", " ");
        }
        if(licence === "PublicDomainLicense") {
            result = "Public Domain";
        }
        return result;
    }

    function determine_type(categories) {
        if (typeof categories !== 'undefined') {
            if (categories.contains("artist")) {
                return "Artist";
            }
            if (categories.contains("person")) {
                return "Person";
            }
            if (categories.contains("yagoGeoEntity")) {
                return "Location";
            }
            if (categories.contains("organization")) {
                return "Organization";
            }
            if (categories.contains("artifact")) {
                return "Artifact";
            }
            if (categories.contains("event")) {
                return "Event";
            }
        } else {
            return "Unknown";
        }
        return "Other";

    }


    String.prototype.replaceAll = function (search, replacement) {
        var target = this;
        return target.split(search).join(replacement);
    };

    Array.prototype.contains = function (needle) {
        var result = false;
        $(this).each(function (index, item) {
            if (item.indexOf(needle) > 0) {
                result = true;

            }
        });
        return result;
    };

    String.prototype.endsWith = function(pattern) {
        var d = this.length - pattern.length;
        return d >= 0 && this.lastIndexOf(pattern) === d;
    };


    function generate_thumbinail_image(imageUrl, widthInPixels, type) {
        if (typeof imageUrl !== 'undefined') {
            var insertIndex = -1;
            var thumbnailUrl = imageUrl;

            if (imageUrl.indexOf("/commons") > 0) {
                insertIndex = imageUrl.indexOf("/commons") + "/commons".length;
            } else if (imageUrl.indexOf("/en") > 0) {
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
            return getDefaultImageByType(type);
        }
    }

    function isFreeImage(imageUrl) {
        if (imageUrl.indexOf("/commons/") != -1) {
            return true;
        }
        return false;
    }


    function getDefaultImageByType(type) {
        var errorImage = unknownImage;

        if (typeof type !== 'undefined') {
            switch (type) {
                case "Artist":
                    errorImage = defaultArtistImage;
                    break;
                case "Person":
                    errorImage = defaultPersonImage;
                    break;
                case "Location":
                    errorImage = defaultLocationImage;
                    break;
                case "Organization":
                    errorImage = defaultOrganizationImage;
                    break;
                case "Artifact":
                    errorImage = defaultArtifactImage;
                    break;
                case "Event":
                    errorImage = defaultEventImage;
                    break;
                case "Unknown":
                    errorImage = unknownImage;
                    break;
                default:
                    errorImage = defaultOtherImage;
                    break;
            }
        }
        return errorImage;
    }

    function nthIndex(str, pat, n) {
        var L = str.length, i = -1;
        while (n-- && i++ < L) {
            i = str.indexOf(pat, i);
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


    function scrollToElement(element) {
        //console.log("EOffset "+element.offset().top+" eld Offset "+$("#entity-linking-demo").offset().top+" eld scrollTop "+$("#entity-linking-demo").scrollTop());
        //$('html,body').animate({scrollTop: element.offset().top - $("#entity-linking-demo").offset().top + $("#entity-linking-demo").scrollTop()}, 1000);
        //return true;
        var entityBoxHeight = $(".entity-box").height();

        var offset = element.offset().top;
        if (!element.is(":visible")) {
            element.css({"visibility": "hidden"}).show();
            var offset = element.offset().top;
            element.css({"visibility": "", "display": ""});
        }

        var visible_area_start = $(window).scrollTop();
        var visible_area_end = visible_area_start + window.innerHeight;
        // console.log("offset "+offset +" visible_area_start="+visible_area_start+" visible_area_end="+visible_area_end);

        if (offset - entityBoxHeight < visible_area_start || offset + entityBoxHeight > visible_area_end) {
            // Not in view so scroll to it
            // console.log("Scrolling to "+ (offset - window.innerHeight/3));
            $('html,body').animate({scrollTop: offset - window.innerHeight / 3}, 1000);
            return false;
        }
        return true;
    }


    var updateState = _.throttle(function () {
        // clear then parameters first
        state = {};

        state['eldText'] = $('#ambiverse-text-input').val();
        state['language'] = $("#settings-language").val();
        state['confidenceThreshold'] = thresholdSlider.slider('getValue');
        state['analyze'] = "true";

        var st = $.param(state);
        history.pushState(null, null, '?' + st + '#entity-linking-demo');
    }, 500, true);

    function resizeListItems() {
        if ($("#ambiverse-result-entities").width() < 900) {
            $("li.list__item").css("width", "25%");
        }
    }

    function upperCaseFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function lowerCaseAllWordsExceptFirstLetters(string) {

        return string.replace(/\w\S*/g, function (word) {
            return word.charAt(0) + word.slice(1).toLowerCase();
        });
    }


})(jQuery);
