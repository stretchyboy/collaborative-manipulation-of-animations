Hash.implement({
    setFromPath: function(path, value) {
        var source = this;
        var prop = '';

        path.replace(/\[([^\]]+)\]|\.([^.[]+)|[^[.]+/g, function(match) {
            if (!source) return;
            prop = arguments[2] || arguments[1] || arguments[0];

            if (!(prop in source)) source[prop] = {};
            lastSource = source;
            source = source[prop];
            return match;
        });

        lastSource[prop] = value;
        return this;
    }
});

