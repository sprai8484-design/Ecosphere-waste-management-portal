-- ================================================================
-- ECOSPHERE REUSE MODULE — DATABASE SETUP
-- Run in phpMyAdmin SQL tab or MySQL CLI
-- ================================================================

CREATE DATABASE IF NOT EXISTS ecosphere_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecosphere_db;

-- ---------------------------------------------------------------
-- TABLE: reuse_ideas
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reuse_ideas (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(200)  NOT NULL,
    category      VARCHAR(60)   NOT NULL  COMMENT 'Plastic | Clothes | Paper | Glass | Wood | Metal | Garden | Electronics',
    difficulty    ENUM('Easy','Medium','Hard') NOT NULL DEFAULT 'Easy',
    time_required VARCHAR(60)   NOT NULL  COMMENT 'e.g. 30 minutes, 2 hours',
    description   TEXT          NOT NULL,
    materials     TEXT          NOT NULL  COMMENT 'Comma-separated list',
    steps         LONGTEXT      NOT NULL  COMMENT 'JSON array of step strings',
    image         VARCHAR(255)  DEFAULT NULL,
    author        VARCHAR(100)  NOT NULL  DEFAULT 'Anonymous',
    likes         INT UNSIGNED  NOT NULL  DEFAULT 0,
    is_approved   TINYINT(1)    NOT NULL  DEFAULT 1  COMMENT '0=pending,1=approved',
    created_at    TIMESTAMP     NOT NULL  DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- TABLE: reuse_comments
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reuse_comments (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    idea_id    INT          NOT NULL,
    name       VARCHAR(100) NOT NULL,
    comment    TEXT         NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idea_id) REFERENCES reuse_ideas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- TABLE: reuse_saved
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reuse_saved (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    idea_id         INT          NOT NULL,
    user_identifier VARCHAR(150) NOT NULL  COMMENT 'Session ID or email',
    saved_at        TIMESTAMP    NOT NULL  DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_save (idea_id, user_identifier),
    FOREIGN KEY (idea_id) REFERENCES reuse_ideas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- SEED DATA — Sample reuse ideas
-- ---------------------------------------------------------------
INSERT INTO reuse_ideas (title, category, difficulty, time_required, description, materials, steps, image, author, likes) VALUES

('Denim Jeans to Tote Bag',
 'Clothes', 'Easy', '45 minutes',
 'Give your worn-out jeans a second life as a stylish, sturdy everyday tote bag. No sewing machine needed — just scissors, needle, and thread. The result is a durable bag that actually looks great.',
 'Old denim jeans,Sharp fabric scissors,Needle and thread (or sewing machine),Rope or fabric handles,Marker pen,Ruler',
 '["Lay your jeans flat on a table. Using your ruler and marker, draw a straight line across both legs about 3 cm below the crotch seam — this will become the base of your bag.", "Cut along the line with your scissors. Set aside the legs (you can use these for handles later).", "Turn the jeans inside out. Pin the cut edge closed and sew a straight seam to seal the bottom of the bag. Use a double-stitch for strength.", "Turn the jeans right-side out again. The waistband becomes the opening of your bag.", "Cut two strips from the leftover jean legs (each about 60 cm long, 4 cm wide). Fold each strip lengthwise and sew the edges to form handles.", "Attach the handles to the inside of the waistband, about 8 cm from the side seams on each side. Sew an X-box pattern for maximum durability.", "Optional: Decorate with fabric paint, iron-on patches, or embroidery to personalise your bag."]',
 NULL, 'Priya Sharma', 284),

('Plastic Bottle Vertical Garden',
 'Plastic', 'Easy', '30 minutes',
 'Transform empty 2-litre PET bottles into a beautiful wall-mounted herb or flower garden. This vertical garden saves space, looks stunning on any balcony wall, and keeps plastic out of landfill.',
 '2-litre plastic bottles (4–6),Craft knife or box cutter,Rope or wire,Hammer and nail,Potting soil,Seeds or small seedlings,Spray paint (optional)',
 '["Collect clean, dry 2-litre bottles. Remove all labels by soaking in warm water.", "Using your craft knife, cut a large rectangular opening (about 15 × 8 cm) on one side of each bottle, positioned 5 cm from the top. This is the planting window.", "Use a heated nail or drill to punch 5–6 small drainage holes in the bottom of each bottle.", "Pierce two holes near the bottle cap using the nail. Thread rope or wire through and knot securely — this is your hanging loop.", "Optional: Spray paint the outside of the bottles in earthy tones. Let dry completely.", "Fill each bottle with a layer of small pebbles for drainage, then potting soil up to 2 cm below the planting window.", "Plant your seeds or seedlings. Water gently. Hang the bottles on any sunny wall or fence, spacing them about 20 cm apart."]',
 NULL, 'Rahul Mehta', 193),

('Glass Jar Ambient Lamp',
 'Glass', 'Easy', '1 hour',
 'Old pasta sauce or pickle jars become beautiful ambient lamps with a warm glow. Line your windowsill or dinner table with several for an incredibly cosy atmosphere. Zero cost, maximum charm.',
 'Large glass jar (any size),Battery-powered fairy lights,Pebbles or coloured marbles,Twine or jute rope,Hot glue gun,Dried flowers or moss (optional)',
 '["Remove the label from your jar by soaking in warm soapy water for 10 minutes, then scrubbing. Dry thoroughly — any moisture will cause condensation.", "Pour a 3 cm layer of pebbles or coloured marbles into the bottom of the jar. This creates a decorative base and holds the lights in place.", "Loosely curl the fairy lights inside the jar, starting from the bottom and working up. Leave the battery pack outside the jar — it should sit on the rim or dangle over the side.", "Wrap twine or jute rope around the neck of the jar 4–5 times and tie securely. A dab of hot glue keeps it in place.", "Optional: Tuck in some dried flowers, a sprig of eucalyptus, or a pinch of preserved moss around the lights for a natural look.", "Turn on the fairy lights and place your lamp on a shelf, windowsill, or bedside table. For best effect, cluster several jars of different sizes together."]',
 NULL, 'Sneha Kulkarni', 347),

('Newspaper Rope Basket',
 'Paper', 'Medium', '2 hours',
 'Roll old newspapers into tight ropes and weave them into a surprisingly strong and stylish basket. This centuries-old craft technique produces baskets sturdy enough for fruit, bread, or remote controls.',
 'Old newspapers (at least 20 full sheets),White glue (PVA),Thick card or cardboard for the base,Acrylic paint,Varnish or Mod Podge,Clothes pegs or clips',
 '["Tear newspaper sheets into strips about 10 cm wide. Roll each strip tightly on the diagonal from corner to corner to form a thin, tight rope. Dab a tiny drop of glue on the final corner to seal. Make at least 30–40 ropes.", "Cut a circle or rectangle from your cardboard for the base. Glue 8–10 ropes pointing outwards from the centre of the base, like spokes of a wheel. Peg each in place until dry.", "Begin weaving: take a long rope and weave it over-then-under the spokes in a continuous spiral. When a spoke ends, simply overlap a new one by 5 cm and continue.", "Use clothes pegs to hold the weaving in place as you go. Keep the rows tight and even by pressing each new row down firmly with your fingers.", "When you reach the desired height, fold the spoke ends down over the last row of weaving and tuck them inside. Glue and peg until set.", "Apply 2 coats of diluted white glue (50:50 with water) over the entire basket to stiffen it. Let dry completely between coats.", "Once fully dry and rigid, paint in any colour you like and seal with varnish or Mod Podge for a lasting finish."]',
 NULL, 'Aditya Patel', 156),

('T-Shirt Tote Bag (No Sew)',
 'Clothes', 'Easy', '20 minutes',
 'Turn any old T-shirt into a reusable shopping bag in under 20 minutes with zero sewing. This is the perfect beginner project — all you need is scissors. The T-shirt fabric naturally stretches, making a flexible, washable bag.',
 'Old T-shirt (size L or XL works best),Sharp scissors,Chalk or fabric marker',
 '["Lay the T-shirt flat on a table. Using chalk, mark a line about 15 cm from the bottom hem all the way around — this will become the base fringe.", "Cut off the sleeves along the seam line. These become part of the bag handles. Also cut off the collar to create a wide opening at the top.", "Now cut 2 cm wide strips along the bottom of the shirt from the hem up to your chalk line. Cut through both layers (front and back) together.", "Tie each front strip to its corresponding back strip with a double knot. Pull firmly — these knots seal the bottom of the bag. Trim any excess.", "Optional: For a neater base, stretch the knotted fringe and tuck all the knots inward, then tie one final strip around them to hold it flat.", "Turn the bag right-side out. The shoulder openings become your handles. Give it a test — it can hold surprisingly heavy loads!"]',
 NULL, 'Kavya Reddy', 421),

('Cardboard Drawer Organiser',
 'Paper', 'Easy', '45 minutes',
 'Cereal boxes and delivery packaging become a perfectly fitted drawer organiser. Customise every compartment size to exactly match what you are storing — cutlery, stationery, makeup, cables. It costs nothing and looks neat.',
 'Empty cereal boxes and cardboard packaging,Ruler and pencil,Sharp scissors or craft knife,Cutting mat,Decorative paper or washi tape,White glue',
 '["Measure the inside of your drawer (length, width, depth). Sketch a rough layout on paper showing how you want to divide the space.", "Cut the cereal boxes down to the right height — typically 5–8 cm for most drawer items. Score the fold lines with a knife and ruler for clean edges.", "Cut strips of cardboard to act as dividers. Their height should match your box walls. Make notches halfway up each intersecting strip so they slot together like a grid.", "Test the fit inside your drawer. Trim any pieces that are too long or wide. The whole organiser should slide in and out smoothly.", "Once you are happy with the fit, decorate each section with coordinating washi tape, decorative paper, or paint. Apply white glue diluted 50:50 as a sealant.", "Leave to dry, then arrange your items. Label each section with a small piece of washi tape if needed."]',
 NULL, 'Meera Joshi', 98),

('Wine Cork Trivet',
 'Wood', 'Easy', '30 minutes',
 'Save wine corks over time and transform them into a beautiful, heat-resistant trivet for your kitchen or dining table. It is a great conversation starter and genuinely protects surfaces from hot pots.',
 'At least 21–30 wine corks (the more uniform the better),Strong waterproof glue (E6000 or Gorilla Glue),Rubber band or tape to hold during drying,Thin cork sheet or felt (for the back, optional)',
 '["Gather your corks and sort them by size. For the best look, use corks that are roughly the same length and diameter.", "Lay the corks out on a flat surface in your chosen pattern. A classic 3×7 grid works well, as does a circular arrangement. Photograph it so you remember the layout.", "Apply a line of strong glue to the side of the first cork, press the second cork firmly against it. Hold for 60 seconds, then apply a rubber band around the whole row.", "Continue gluing one row at a time. Let each row cure for 10 minutes before adding the next. Keep the surface flat.", "Once all corks are joined, leave the trivet to cure fully for at least 2 hours (or overnight for maximum strength).", "Optional: Cut a piece of thin cork sheet or felt to the same shape and glue it to the back. This prevents scratching the table surface.", "Your trivet is heat-resistant up to about 200°C and water-resistant once the glue has cured. Hand wash only."]',
 NULL, 'Vikram Nair', 72),

('E-Waste Component Art Frame',
 'Electronics', 'Hard', '3 hours',
 'Old circuit boards, resistors, capacitors, and wiring become a stunning piece of wall art. This is a great way to appreciate the beauty of electronics while keeping toxic components out of general waste — take them to a certified e-waste recycler after displaying them.',
 'Old PCB / circuit board,Assorted electronic components (resistors, capacitors, chips),Shadow box or deep picture frame,Black velvet or card for backing,Strong clear adhesive (epoxy or E6000),Tweezers,Rubbing alcohol for cleaning',
 '["Clean all components with a small amount of rubbing alcohol on a cloth. Remove any dust or flux residue from the PCB. Let dry completely.", "Cut your black velvet or card to fit inside the shadow box frame. This dark background makes the components pop visually.", "Begin with the PCB as your centrepiece. Position it on the backing and mark its location lightly in pencil.", "Arrange additional loose components around the PCB — group them by type and colour for a pleasing composition. Step back and photograph your arrangement before gluing.", "Apply a small dot of epoxy or E6000 to each component and press it firmly in position. Start with the largest pieces first, then fill gaps with smaller ones.", "Allow the adhesive to cure fully (usually 24 hours for epoxy). Do not move the frame during this time.", "Hang your artwork where it gets good light. The solder joints and copper traces catch the light beautifully. If anyone asks — tell them where these components came from and why recycling e-waste matters."]',
 NULL, 'Arjun Das', 134);

-- ---------------------------------------------------------------
-- SEED DATA — Sample comments
-- ---------------------------------------------------------------
INSERT INTO reuse_comments (idea_id, name, comment) VALUES
(1, 'Ananya S.',     'Made this last weekend and it came out even better than I expected! Used the leg strips as handles — much stronger than rope.'),
(1, 'Prachi M.',     'This is my third one now. I sell them at our local market. The pocket from the back jeans pocket is perfect for a phone.'),
(3, 'Dev Kumar',     'I made six of these in one evening for a dinner party. My guests kept asking where I bought them. Best zero-cost project ever.'),
(3, 'Lalitha R.',    'Used old pickle jars and gold fairy lights. The warm glow is incredible. Highly recommend adding dried lavender inside.'),
(5, 'Sunita V.',     'So easy! Did this with my 10-year-old. We made five bags in an hour. Now we never forget our bags at the supermarket.'),
(5, 'Harsh P.',      'Great idea — stretched mine a little wider at the handles. Fits perfectly over my shoulder.'),
(2, 'Nimisha G.',    'Growing basil, mint, and chilli in mine. The wall looks amazing and my kitchen smells great!');
