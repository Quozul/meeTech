class Statistics {
    constructor(options = {}) {
        this._options = options;
    }

    setStats(stats) {
        if (typeof stats == "object" && Array.isArray(stats)) {
            this._max_value = 0;
            this._x_axes = [];
            this._y_axes = [];

            // verification & finding max
            stats.forEach((e, i) => {
                if (typeof e != "object" || !Array.isArray(e))
                    stats[i] = [i, e];

                this._max_value = Math.max(this._max_value, stats[i][1]);
                if (!this._x_axes.includes(stats[i][0])) this._x_axes.push(stats[i][0]);
                if (!this._y_axes.includes(stats[i][1])) this._y_axes.push(stats[i][1]);
            });


            this._stats = stats;

            console.log('Stats updated! Max value: ' + this._max_value);
        } else {
            console.error('Type of object isn\'t valid.');
        }
    }

    /**
     * 
     * @param {object} options {.font, .bg_color, .fill_color}
     */
    setOptions(options) {
        this._options = options;
    }

    drawPoint(ctx, x, y) {
        x = Math.floor(x);
        y = Math.floor(y);

        ctx.beginPath();

        ctx.arc(x, y, 4, 0, Math.PI * 2);
        ctx.fill();

        console.log(`Drew point at ${x};${y}`);
    }

    drawLine(ctx, x1 = 0, y1 = 0, x2, y2, style = 'white') {
        ctx.beginPath();
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.strokeStyle = this._options.fill_color || style;
        ctx.stroke();

        console.log(`Drew line at ${x1};${y1}`);
    }

    drawRect(ctx, x, y, w, h) {
        ctx.fillRect(x, y, w, h);

        console.log(`Drew rectangle at ${x};${y}`);
    }

    drawText(ctx, text, x, y) {
        ctx.font = this._options.font || '12px sans-serif';
        let m = ctx.measureText(text);
        ctx.fillText(text, x - ctx.measureText(text).width / 2, y - 6);
    }

    /**
     * 
     * @param {string} t dot (default) | line | curve (smoothed lines) | bars
     */
    setChartType(t) {
        if (this._stats != undefined) {
            this._chart_type = t;
            console.log('Chart type updated!');
        } else {
            console.error('Stats aren\'t defined.');
        }
    }

    draw(canvas, hscale = 1, vscale) {
        if (vscale == undefined) vscale = hscale;
        if (this._stats != undefined) {
            canvas.width = window.innerWidth * hscale;
            canvas.height = window.innerHeight * vscale;

            const pad_left = 50;
            const pad_right = 5;
            const pad_top = 10;
            const pad_bottom = 25;

            let innerWidth = canvas.width - pad_left - pad_right;
            let innerHeight = canvas.height - pad_top - pad_bottom;

            let ctx = canvas.getContext('2d');

            ctx.fillStyle = this._options.bg_color || 'black';
            ctx.fillRect(pad_left, pad_top, innerWidth, innerHeight);

            ctx.fillStyle = this._options.fill_color || 'white';

            // draw curve
            let stats_length = 0;
            if (typeof this._stats[0] == "object" && typeof this._stats[0][0] == "number")
                this._stats.forEach(e => {
                    stats_length = Math.max(stats_length, e[0]);
                });
            else
                stats_length = this._stats.length - 1;

            console.log(`Stats length: ${stats_length}`);

            this._stats.forEach((e, i, a) => {
                let x = (typeof e[0] == "number" ? e[0] : i) / stats_length * innerWidth + pad_left;
                let y = (this._max_value - e[1]) / this._max_value * innerHeight + pad_top;

                if (this._chart_type == 'line' && i != 0) {
                    let x1, y1;
                    if (i - 1 >= 0) {
                        x1 = a[i - 1][0] / stats_length * innerWidth + pad_left;
                        y1 = (this._max_value - a[i - 1][1]) / this._max_value * innerHeight + pad_top;
                    }

                    this.drawLine(ctx, x1, y1, x, y);
                } else if (['dot', 'dots', 'point', 'points'].includes(this._chart_type))
                    this.drawPoint(ctx, x, y);
                else if (this._chart_type == 'bars') {
                    let h = e[1] / this._max_value * innerHeight + pad_top;
                    this.drawRect(ctx, x - 5, innerHeight - h + pad_top * 2, 10, h - pad_top);
                }
            });

            this._x_axes.forEach((e, i) => {
                let x = (typeof e == "number" ? e : i) / stats_length * innerWidth + pad_left;
                if (e != 0)
                    this.drawText(ctx, e, x, canvas.height - 8);

                // draw vertical line
                this.drawLine(ctx, x, 0 + pad_top, x, innerHeight + pad_top, 'rgba(255, 255, 255, .5)');
            });

            this._y_axes.forEach((e, i) => {
                let y = (this._max_value - e) / this._max_value * innerHeight + pad_top;
                if (e != 0)
                    this.drawText(ctx, e, 12, y + 10);

                // draw horizontal line
                this.drawLine(ctx, pad_left, y, innerWidth + pad_left, y, 'rgba(255, 255, 255, .5)');
            });

            if (!this._y_axes.includes(0)) {
                let y = (this._max_value - 0) / this._max_value * innerHeight + pad_top;
                this.drawLine(ctx, pad_left, y, innerWidth + pad_left, y, 'rgba(255, 255, 255, .5)');
            }
        } else {
            console.error('Stats aren\'t defined.');
        }
    }
}