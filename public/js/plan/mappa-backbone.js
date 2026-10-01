function requestAnimFrame(c) {
    try {
        return window.requestAnimationFrame(c)
    } catch (d) {
        try {
            return window.webkitRequestAnimationFrame(c)
        } catch (d) {
            return window.mozRequestAnimationFrame(c)
        }
    }
}

function cancelAnimFrame(c) {
    try {
        return window.CancelAnimationFrame(c)
    } catch (d) {
        try {
            return window.webkitCancelAnimationFrame(c)
        } catch (d) {
            return window.cancelAnimationFrame(c)
        }
    }
}

function getOffsets(c) {
    var d = [];
    if (typeof(c.offsetX) != "undefined") {
        mx = c.offsetX;
        my = c.offsetY
    } else {
        mx = parseInt(c.clientX - $(c.target).offset().left);
        my = parseInt(c.pageY - $(c.target).offset().top) - 2
    }
    d.x = mx;
    d.y = my;
    return d
}
ImageMap = Backbone.RelationalModel.extend({
    relations: [{
        type: Backbone.HasMany,
        key: "areas",
        relatedModel: "Area",
        reverseRelation: {
            key: "mappa"
        },
        collectionType: "Areas"
    }],
    subModelTypes: {
        polygon: "Polygon",
        rect: "Rectangle",
        circle: "Circle"
    },
    subModelTypeAttribute: "shape",
    defaults: {
        name: "imagemap"
    },
    getMapTag: function() {
        var b = "";
        b += "<% _.each(areas, function(a) { %><%= a %><% }); %>";
        return _.template(b, this.getViewModel())
    },
    getMapHTMLTag: function() {
        var b = "";
        b += "<% _.each(areas, function(a) { %><%= a %><% }); %>";
        return _.template(b, this.getHTMLViewModel())
    },
    getViewModel: function() {
        var b = {
            name: this.get("name")
        };
        b.areas = this.get("areas").map(function(a) {
            return a.getAreaTag()
        });
        return b
    },
    getHTMLViewModel: function() {
        var b = {
            name: this.get("name")
        };
        b.areas = this.get("areas").map(function(a) {
            return a.getHTMLAreaTag()
        });
        return b
    }
});
Area = Backbone.RelationalModel.extend({
    relations: [{
        type: Backbone.HasMany,
        key: "points",
        relatedModel: "Point",
        collectionType: "Points",
        reverseRelation: {
            key: "area"
        }
    }],
    defaults: {
        selected: false,
        shape: null,
        href: null,
        alt: null,
        attrs: []
    },
    updateOffset: function(f, g) {
        var h = f - this.offsetX,
            e = g - this.offsetY;
        this.get("points").forEach(function(a) {
            a.set("x", a.get("x") + h);
            a.set("y", a.get("y") + e)
        });
        this.offsetX = f;
        this.offsetY = g
    },
    getAreaTag: function() {
        var b = "";
        model = this.getViewModel();
        b += "{";
        b += '"shape": "<%=shape%>",';
        b += '"selected": false,';
        b += '"points":[';
        b += "<%=coords%>";
        b += "]";
        b += "},\n";
        return _.template(b, model)
    },
    getHTMLAreaTag: function() {
        var c = "",
            d = this.getHTMLViewModel();
        c += " <area\n";
        c += ' shape="<%=shape%>"\n';
        c += ' href=""\n';
        c += ' alt="<%=alt%>"\n';
        c += ' coords="<%=coords%>"\n';
        c += '<% _.each(attrs, function(a) { %> <%= a.key %>="<%= a.value %>"\n<% }); %>';
        c += " />\n";
        return _.template(c, d)
    },
    getCoords: function() {
        return this.get("points").reduce(function(g, h, f, l) {
            var k = g + h.get("x") + "," + h.get("y");
            if (f < l.length - 1) {
                k += ","
            }
            return k
        }, "")
    },
    getHTMLViewModel: function() {
        var b = {
            shape: this.get("shape"),
            href: this.get("floor"),
            alt: this.get("alt"),
            attrs: this.get("attrs"),
            coords: this.getCoords()
        };
        return b
    },
    getViewModel: function() {
        var g = "";
        var h = this.getCoords();
        var e = h.split(",");
        for (i = 0; i < e.length - 1; i++) {
            if (i % 2 == 0) {
                g += '{"x":' + e[i] + ',"y":' + e[i + 1] + "}";
                j = i - 2;
                if (j < i) {
                    g += ","
                }
            }
        }
        var f = {
            shape: this.get("shape"),
            href: this.get("floor"),
            alt: this.get("alt"),
            attrs: this.get("attrs"),
            coords: g
        };
        return f
    },
    deleteMousedOverPoint: function(k, l, n, m) {
        var g = this.isMousedOverPoint;
        var h = this.get("points").find(function(a) {
            return g(k, l, a, n, m)
        });
        if (h) {
            this.get("points").remove(h)
        }
        if (this.get("points").length === 0 && this.collection && this.collection.remove) {
            this.collection.remove(this)
        }
    },
    isMousedOverPoint: function(m, n, h, p, o) {
        var k, l;
        if (h.x) {
            k = h.x;
            l = h.y
        } else {
            if (h.get) {
                k = h.get("x");
                l = h.get("y")
            } else {
                return false
            }
        }
        return (m >= k - p / 2 && m <= k + p / 2 && n >= l - o / 2 && n <= l + o / 2)
    },
    isMousedOver: function(l, m) {
        var p, h, n = false;
        var o = this.get("points").toJSON();
        for (p = 0, h = o.length - 1; p < o.length; h = p++) {
            if (((o[p].y > m) != (o[h].y > m)) && (l < (o[h].x - o[p].x) * (m - o[p].y) / (o[h].y - o[p].y) + o[p].x)) {
                n = !n
            }
        }
        if (!n) {
            var k = this.isMousedOverPoint;
            o.forEach(function(a) {
                if (k(l, m, a, 5, 5)) {
                    n = true
                }
            })
        }
        return n
    }
});
Areas = Backbone.Collection.extend({
    model: Area
});
Polygon = Area.extend({
    defaults: {
        shape: "polygon"
    }
});
Rectangle = Area.extend({
    defaults: {
        shape: "rect"
    }
});
Circle = Area.extend({
    defaults: {
        shape: "circle",
        radius: null
    },
    getCoords: function() {
        var b = Area.prototype.getCoords.apply(this);
        b += "," + this.get("radius");
        return b
    }
});
Point = Backbone.RelationalModel.extend({
    defaults: {
        x: null,
        y: null
    }
});
Points = Backbone.Collection.extend({
    model: Point,
    getPoints: function() {
        return this.models.map(function(b) {
            return {
                x: b.get("x"),
                y: b.get("y")
            }
        })
    }
});
var TOOL_ARROW = "arrow";
var TOOL_DELETE = "delete";
var TOOL_POLYGON = "polygon";
MapView = Backbone.View.extend({
    events: {
        "mousemove canvas": "mouseMove",
        "mousedown canvas": "mouseDown",
        "mouseup canvas": "mouseUp",
        dragover: "dragOver",
        dragenter: "dragOver",
        "change input": "changeTool",
        "click .selected-true input": "deleteArea",
        "change .mappa-list select": "updateAttribute",
        "paste textarea": "pasteImageTag"
    },
    pasteImageTag: function(b) {
        this.parseImageTag(b.target.value)
    },
    parseImageTag: function(b) {
        alert("Sorry - pasting not supported yet")
    },
    initialize: function() {
        _.bindAll(this, "onLoadImage", "renderArea", "rafRender", "createAreaView", "clearCanvas", "drop");
        this.mouseMove = _.throttle(this.mouseMove, 1000 / 60);
        this.current_tool = TOOL_ARROW;
        this.model = new ImageMap(map);
        this.canvas = this.el.querySelector("canvas");
        this.context = this.canvas.getContext("2d");
        this.createAreaViews();
        this.el.addEventListener("drop", this.drop)
    },
    deleteArea: function(c) {
        var d = parseInt(c.target.parentNode.getAttribute("data-index"), 10);
        this.model.get("areas").at(d).destroy();
        this.render()
    },
    dragOver: function(b) {
        b.preventDefault();
        this.$el.addClass("hover");
        return false
    },
    dragEnter: function(b) {
        b.preventDefault();
        this.$el.addClass("hover");
        return false
    },
    dragEnd: function(b) {
        this.$el.removeClass("hover");
        return false
    },
    drop: function(h) {
        this.$el.removeClass("hover");
        h.preventDefault();
        if (!h.dataTransfer.files.length) {
            return
        }
        var f = ["image/jpg", "image/png", "image/gif", "image/webm"];
        if (f.indexOf(h.dataTransfer.files[0].type) === -1) {
            return
        }
        var g = new FileReader();
        var e = this.image;
        g.onload = function(a) {
            e.src = a.target.result
        };
        g.readAsDataURL(h.dataTransfer.files[0]);
        return false
    },
    updateAttribute: function(f) {
        var e = f.target;
        var d = parseInt(e.parentNode.parentNode.getAttribute("data-index"), 10);
        this.model.get("areas").at(d).set(e.getAttribute("name"), e.value);
        this.render(true)
    },
    updateHTML: function() {
        var c = this.model.getMapTag();
        var d = this.model.getMapHTMLTag();
        this.$("textarea.mappa-html").val(c);
        this.$("textarea.mappa-area").val(d);
        if (!this.dont_render_list) {
            this.$(".mappa-list").html(this.getList())
        }
    },
    getList: function() {
        var c = "";
        c += "<% _(areas).each(function(area, index) { %>";
        c += '<li data-index="<%= index %>" class="selected-<%= area.selected %>">';
        c += '<input id="deleteArea" class="delete input_hidden" type="radio" value="deleteArea" name="tool"><label class="actionBtn tip deletePoint" for="deleteArea" title="Usuń element"><i class="fe-trash-2"></i> Usuń element</label>';
        c += "</li>";
        c += "<% }); %>";
        var d = _.template(c, this.model.toJSON());
        return d
    },
    onLoadImage: function() {
        this.canvas.width = this.image.width;
        this.canvas.height = this.image.height;
        this.render()
    },
    loadImage: function(b) {
        this.image_url = b;
        this.image = new Image();
        this.image.onload = this.onLoadImage;
        this.image.src = b
    },
    createAreaViews: function() {
        if (!this.area_views) {
            this.area_views = []
        }
        this.model.get("areas").forEach(this.createAreaView)
    },
    createAreaView: function(c) {
        var d;
        switch (c.get("shape")) {
            case "polygon":
                d = new PolygonView({
                    model: c
                });
                break;
            case "rect":
                d = new RectangleView({
                    model: c
                });
                break;
            case "circle":
                d = new CircleView({
                    model: c
                });
                break
        }
        this.area_views.push(d)
    },
    render: function(b) {
        this.dont_render_list = b;
        if (this.rafId) {
            cancelAnimFrame(this.rafId)
        }
        this.rafId = requestAnimFrame(this.rafRender)
    },
    rafRender: function() {
        this.updateHTML();
        this.clearCanvas();
        this.renderImage();
        this.renderAreas();
        this.renderPoints()
    },
    clearCanvas: function() {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height)
    },
    renderImage: function() {
        this.context.drawImage(this.image, 0, 0)
    },
    renderAreas: function() {
        var b = this;
        this.area_views.forEach(this.renderArea)
    },
    renderArea: function(b) {
        b.render(this.context)
    },
    renderPoints: function() {
        var b = this.context;
        this.area_views.forEach(function(a) {
            if (a.model.get("selected") || a.model.highlighted) {
                a.model.get("points").forEach(function(q, o, m) {
                    if (o === 0) {
                        b.fillStyle = "#f00"
                    } else {
                        if (o === m.length - 1) {
                            b.fillStyle = "#000"
                        } else {
                            b.fillStyle = "#0ff"
                        }
                    }
                    var p = 5,
                        n = 5,
                        r = q.get("x") - p / 2,
                        h = q.get("y") - n / 2;
                    b.fillRect(r, h, p, n);
                    b.closePath()
                })
            }
        })
    },
    mouseMove: function(f) {
        var d = this;
        var e = getOffsets(f);
        f.offsetX = e.x;
        f.offsetY = e.y;
        if (this.moving_point) {
            this.moving_point.set("x", f.offsetX);
            this.moving_point.set("y", f.offsetY)
        } else {
            if (this.moving_view) {
                this.moving_view.model.updateOffset(f.offsetX, f.offsetY)
            }
        }
        this.area_views.forEach(function(c, b) {
            var a = c.model.isMousedOver(f.offsetX, f.offsetY);
            c.model.highlighted = a
        });
        this.render()
    },
    mouseDown: function(n) {
        var e = this,
            p, l;
        var k = getOffsets(n);
        var m = k.x;
        var o = k.y;
        if (this.current_tool === TOOL_ARROW) {
            this.area_views.forEach(function(c, b) {
                c.model.get("points").forEach(function(d) {
                    if (c.model.isMousedOverPoint(m, o, d, 5, 5)) {
                        p = c;
                        l = d
                    }
                });
                if (!e.moving_view) {
                    var a = c.model.isMousedOver(m, o);
                    c.model.set("selected", a);
                    if (a) {
                        p = c;
                        c.model.offsetX = m;
                        c.model.offsetY = o
                    }
                }
            });
            e.moving_view = p;
            e.moving_point = l;
            n.target.style.cursor = "move";
            this.render()
        }
        return false
    },
    mouseUp: function(g) {
        var f = getOffsets(g);
        g.offsetX = f.x;
        g.offsetY = f.y;
        if (this.adding_view && this.current_tool === TOOL_POLYGON) {
            this.adding_view.model.get("points").add({
                x: g.offsetX,
                y: g.offsetY
            });
            this.render()
        } else {
            if (this.current_tool === TOOL_POLYGON) {
                var h = new Polygon({
                    points: [{
                        x: g.offsetX,
                        y: g.offsetY
                    }],
                    selected: true
                });
                var e = new PolygonView({
                    model: h
                });
                this.model.get("areas").add(h);
                this.area_views.push(e);
                this.adding_view = e;
                this.render()
            } else {
                if (this.adding_view && this.current_tool === TOOL_DELETE) {
                    this.adding_view.model.deleteMousedOverPoint(g.offsetX, g.offsetY, 5, 5);
                    this.render()
                } else {
                    this.adding_view = this.moving_view
                }
            }
        }
        this.moving_view = null;
        this.moving_point = null;
        g.target.style.cursor = ""
    },
    changeTool: function(b) {
        if (b.target.checked) {
            this.current_tool = b.target.value
        }
    }
});
AreaView = Backbone.View.extend({
    contextAttributesUnselected: {
        fillStyle: "rgba(0, 197, 221, 0.6)",
        strokeStyle: "rgba(0, 197, 221, 0)",
        lineWidth: 0
    },
    contextAttributesSelected: {
        fillStyle: "rgba(0, 197, 221, 0.9)",
        strokeStyle: "#000",
        lineWidth: 1
    },
    setupContext: function(d) {
        var c = (this.model.get("selected")) ? this.contextAttributesSelected : this.contextAttributesUnselected;
        this.applyContextAttributes(d, c);
        return c
    },
    applyContextAttributes: function(c, d) {
        _(d).each(function(a, b) {
            c[b] = a
        })
    }
});
PolygonView = AreaView.extend({
    render: function(e) {
        var d = this.model.get("points").getPoints();
        var f = this.setupContext(e);
        if (!d[0]) {
            return
        }
        e.moveTo(d[0].x, d[0].y);
        e.beginPath();
        d.forEach(function(b, a) {
            if (!a) {
                return
            }
            e.lineTo(b.x, b.y)
        });
        e.lineTo(d[0].x, d[0].y);
        e.closePath();
        e.fill();
        e.stroke()
    }
});
