class PuzzlePart {
    constructor(ctx, canvas, img, x, y, rw, rh, cx, cy) {
        this.x = cx;
        this.y = cy;
        this.w = rw;
        this.h = rh;
        this.img = img;
        this.canvas = canvas;

        ctx.drawImage(img,
            x, y,
            rw, rh,
            cx, cy,
            rw, rh
        );
    }
}

function drawCaptcha(puzzle_canvas, image_path, action) {
    const part_count = 4;
    const font_size = 60;
    const space_between = font_size + 10;

    const ctx = puzzle_canvas.getContext('2d');

    const text = 'Cliquez sur la pièce manquante parmi celles ci-dessous :'

    function in_square(x, y, x1, y1, w, h) {
        return x < x1 + w && x1 < x && y < y1 + h && y1 < y;
    }

    const img = new Image();
    img.src = image_path;

    let parts = new Array(); // this array stores the puzzle
    let missing_part = Math.floor(Math.random() * Math.pow(part_count, 2)); // this is the missing part from the puzzle
    let remaining_parts = new Array(); // this array stores drawed parts
    let remaining_parts_id = new Array(); // this array stores parts ids

    /**
     * Shuffles array in place.
     * @param {Array} a items An array containing the items.
     */
    function shuffle(a) {
        for (let i = a.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [a[i], a[j]] = [a[j], a[i]];
        }
        return a;
    }

    img.onload = function () {
        const part_width = img.width / part_count;
        const part_height = img.height / part_count;

        puzzle_canvas.width = img.width;
        puzzle_canvas.height = img.height + part_height + space_between;

        const max_parts = Math.pow(part_count, 2);

        // draw parts
        for (let x = 0; x < this.width; x += part_width) {
            for (let y = 0; y < this.height; y += part_height) {

                // get col and row on grid
                const col = x / part_width;
                const row = y / part_height;

                // skip the missing part
                if (row + part_count * col == missing_part) continue;

                const a = Math.floor(x / part_width), b = Math.floor(y / part_height);
                const part = b + part_count * a;

                parts.push(new PuzzlePart(ctx, puzzle_canvas, img, x, y, part_width, part_height, x, y));
            }
        }

        // draw grid
        ctx.lineWidth = 5;
        for (let x = 0; x < this.width; x += part_width) {
            for (let y = 0; y < this.height; y += part_height) {
                // draw grid on puzzle
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x, img.height);

                ctx.moveTo(0, y);
                ctx.lineTo(img.width, y);
                ctx.stroke();
            }
        }

        // add the missing part's id to the array
        remaining_parts_id.push(missing_part);

        // get remaining part ids
        for (let i = 0; i < part_count - 1; i++) {
            let r = Math.floor(Math.random() * max_parts);
            if (!remaining_parts_id.includes(r))
                remaining_parts_id.push(r);
            else
                i--;
        }

        shuffle(remaining_parts_id);

        // draw remaining parts
        remaining_parts_id.forEach((i, k) => {
            // get position on the image
            const x = Math.floor(i / part_count) * part_width;
            const y = i % part_count * part_height;

            // get position on the canvas
            const cx = k * part_width;
            const cy = img.height + space_between;

            remaining_parts.push(new Array(i, new PuzzlePart(ctx, puzzle_canvas, img, x, y, part_width, part_height, cx, cy)));
        });

        // draw instruction
        ctx.fillStyle = 'white';
        ctx.font = font_size + 'px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(text, puzzle_canvas.width / 2, part_height * part_count + 50);
    }

    // get mouse position on canvas
    function getMousePos(img, canvas, e) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        }
    }

    // when user click on canvas
    puzzle_canvas.onmousedown = function (e) {
        const rect = this.getBoundingClientRect();
        const mp = getMousePos(img, this, e);

        remaining_parts.forEach((p, key) => {
            const id = p[0];
            const part = p[1];

            const px = part.x / puzzle_canvas.width * rect.width;
            const py = part.y / puzzle_canvas.height * rect.height;

            const pw = part.w / puzzle_canvas.width * rect.width;
            const ph = part.h / puzzle_canvas.height * rect.height;

            // do something when right part is clicked
            if (in_square(mp.x, mp.y, px, py, pw, ph) && id == missing_part)
                action();
            else if (in_square(mp.x, mp.y, px, py, pw, ph)) {
                ctx.beginPath();
                ctx.moveTo(part.x, part.y);
                ctx.lineTo(part.x + part.w, part.y + part.h);
                ctx.stroke();

                ctx.beginPath();
                ctx.moveTo(part.x + part.w, part.y);
                ctx.lineTo(part.x, part.y + part.h);
                ctx.stroke();
            }
        });
    }
}