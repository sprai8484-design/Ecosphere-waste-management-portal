-- ================================================================
-- ECOSPHERE BLOG MODULE — DATABASE SETUP
-- Run this entire file in phpMyAdmin → SQL tab → Go
-- ================================================================

CREATE DATABASE IF NOT EXISTS ecosphere_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecosphere_db;

-- ---------------------------------------------------------------
-- TABLE: blogs
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS blogs (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(300)                          NOT NULL,
    content      LONGTEXT                              NOT NULL,
    image        VARCHAR(255)                          DEFAULT NULL,
    author_name  VARCHAR(100)                          NOT NULL,
    email        VARCHAR(150)                          NOT NULL,
    status       ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    admin_note   TEXT                                  DEFAULT NULL,
    reviewed_by  VARCHAR(100)                          DEFAULT NULL,
    reviewed_at  TIMESTAMP                              NULL,
    created_at   TIMESTAMP                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP                             NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- TABLE: blog_admin_users
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS blog_admin_users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(60)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL COMMENT 'SHA-256 hashed',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin: username=admin, password=admin123
INSERT IGNORE INTO blog_admin_users (username, password)
VALUES ('admin', SHA2('admin123', 256));

-- ---------------------------------------------------------------
-- SEED DATA — Sample approved blogs for demo
-- ---------------------------------------------------------------
INSERT INTO blogs (title, content, image, author_name, email, status, admin_note, reviewed_by, reviewed_at) VALUES

('The Zero-Waste Kitchen: 7 Habits That Actually Work',
'<p>Building a zero-waste kitchen does not require a dramatic overhaul of how you shop, cook, or eat. It is built from small, deliberate habits repeated over months until they become second nature. After three years of working toward a genuinely low-waste home kitchen, here are the seven changes that made the biggest measurable difference.</p>

<h3>1. The Glass Jar System</h3>
<p>Replace all plastic storage with glass jars of varying sizes. Pasta, lentils, nuts, oats, spices — everything gets a jar. The visual clarity means you never forget what you have, and you stop buying duplicates. When a jar is empty, it goes straight to the dishwasher and back to the shelf. No single-use plastic bags, no cling film.</p>

<h3>2. Buy at the Source</h3>
<p>Farmers markets and bulk stores are not just trendy — they are structurally different from supermarkets. Most of what you buy comes without packaging at all. A cloth produce bag, a few reusable containers for wet goods, and you are carrying home vegetables, grains and legumes with zero packaging footprint.</p>

<h3>3. The Weekly Food Audit</h3>
<p>Every Sunday morning, spend five minutes looking at what needs to be used first. This single habit cuts food waste by around 40% in most households. Build that week's meals around what is already in the fridge rather than buying everything fresh. Wilting greens become soup. Overripe fruit becomes smoothies or crumble.</p>

<h3>4. Compost Everything Possible</h3>
<p>Even a small apartment can compost with a Bokashi system or a compact vermicompost bin. The reduction in bin waste is significant — food scraps account for roughly 30% of household landfill contributions. Compost returns nutrients to soil instead of generating methane in landfill.</p>

<h3>5. Make Your Own Cleaners</h3>
<p>A spray bottle of white vinegar diluted with water handles most kitchen cleaning. Bicarbonate of soda and castile soap handle the rest. The packaging footprint shrinks dramatically. The cost savings are considerable. And the chemical load in your kitchen drops to near zero.</p>

<h3>6. Cloth Over Paper</h3>
<p>Replace paper towels with a stack of cloth rags cut from old t-shirts. Wash with normal laundry. The cloth rags last years. The environmental difference over a decade is enormous — a single household consumes roughly 80 rolls of paper towels annually.</p>

<h3>7. Track Your Progress</h3>
<p>Photograph your bin contents weekly for a month. Most people are genuinely surprised by what they throw away. The act of tracking creates awareness, and awareness drives change. Even one change per month compounded over a year produces a meaningfully different relationship with waste.</p>',
NULL, 'Meera Krishnamurthy', 'meera@example.com', 'approved',
'Beautifully written, highly practical, directly relevant to waste management. Approved.', 'admin', NOW()),

('Why India''s E-Waste Crisis Needs Your Attention Right Now',
'<p>India generates over 3.2 million metric tonnes of electronic waste every year, making it the third-largest e-waste producer in the world. Less than 5% of it is recycled through formal, certified channels. The rest is dismantled by hand in unregulated workshops, often by children, using acid baths and open burning to recover tiny quantities of metal.</p>

<h3>The Scale of the Problem</h3>
<p>Your old phone, laptop, television, or microwave does not simply disappear when you throw it away or hand it to an itinerant scrap collector. It enters an informal recycling economy that is simultaneously economically vital and environmentally devastating. Workers are exposed daily to lead, mercury, cadmium, and brominated flame retardants — substances linked to neurological damage, cancer, and reproductive harm.</p>

<h3>What Formal Recycling Actually Means</h3>
<p>Certified e-waste recyclers use enclosed processes that capture toxic materials rather than releasing them into air, water, and soil. They recover valuable metals — gold, silver, copper, palladium — in quantities that make the process economically viable without environmental shortcuts. The material streams are tracked and audited. Workers have protective equipment and health monitoring.</p>

<h3>The Extended Producer Responsibility Gap</h3>
<p>India''s E-Waste Management Rules (2022) place legal responsibility on manufacturers to collect and recycle their products at end of life. Most are not meeting their targets. The penalties are weak. The collection infrastructure is underdeveloped. Consumers are not given clear, convenient options for responsible disposal.</p>

<h3>What You Can Do</h3>
<p>First, extend the life of every device you own. The most sustainable device is the one you already have. When a device genuinely cannot be repaired or reused, find a certified recycler through the CPCB-authorised recycler registry. Do not hand electronics to door-to-door scrap collectors unless you have verified their disposal methods. Participate in manufacturer take-back programmes when they exist.</p>

<p>The infrastructure for responsible e-waste management exists. It needs to be used.</p>',
NULL, 'Arjun Shetty', 'arjun@example.com', 'approved',
'Thoroughly researched, important topic, well within scope. Approved.', 'admin', NOW()),

('Composting in a Mumbai Flat: My 6-Month Experiment',
'<p>I live in a 650 square foot apartment on the fourteenth floor of a building in Andheri West. I have no garden, no balcony large enough for a traditional compost bin, and neighbours who were initially sceptical that I was about to make the kitchen smell terrible. Six months later, I have a working compost system, no smell whatsoever, and a waiting list of neighbours who want to start their own.</p>

<h3>What I Use: The Khamba System</h3>
<p>A Khamba is a stackable set of terracotta pots designed specifically for apartment composting in Indian conditions. The terracotta breathes, regulating moisture naturally. The stacking design means it takes up roughly the same floor space as a large water bottle. It sits on my kitchen counter, next to the bin.</p>

<h3>The Input</h3>
<p>Vegetable peels, fruit scraps, used tea leaves, coffee grounds, eggshells, cooked food (in small quantities), paper napkins, and cardboard torn small. What I do not add: meat, fish, large quantities of oily food, or anything synthetic.</p>

<h3>The Process</h3>
<p>Every time I add kitchen scraps, I add an equal layer of dry material — shredded newspaper, dry leaves I collect from the building compound, or cocopeat. This balance is the key to odour control. Without dry material, compost gets wet and anaerobic. With it, the microbial process stays aerobic and smell-free.</p>

<h3>The Results</h3>
<p>After six months: approximately 2 kg of finished compost that looks and smells like rich garden soil. My kitchen waste output to the building''s bin has reduced by roughly 60%. Several neighbours have started their own systems. The building''s resident welfare association is now discussing a community composting programme for the rooftop garden.</p>

<p>The barrier to apartment composting is almost entirely psychological. The reality is clean, manageable, and genuinely satisfying.</p>',
NULL, 'Priya Deshmukh', 'priya@example.com', 'approved',
'Practical, relatable, excellent for urban readers. Approved.', 'admin', NOW()),

('The True Cost of Fast Fashion''s Textile Waste',
'<p>The global fashion industry produces between 92 and 100 million tonnes of textile waste every year. A significant proportion ends up in landfill within twelve months of manufacture — often without being worn at all. Understanding where this waste goes, and why the current system produces it so efficiently, is the first step toward changing individual and collective behaviour.</p>

<h3>The Economics of Overproduction</h3>
<p>Fast fashion brands overproduce by design. Excess inventory that cannot be discounted and sold is cheaper to destroy than to store, ship back, or donate. Burning unsold stock — a practice recently made illegal in France and under discussion in the EU — was the standard approach for many major brands until media exposure forced partial policy changes. The incentive structure still rewards overproduction.</p>

<h3>The Polyester Problem</h3>
<p>Approximately 60% of clothing is made from polyester, derived from petroleum. It does not biodegrade. When washed, it sheds microplastic fibres that pass through wastewater treatment systems and accumulate in marine environments. The clothing that reaches landfill locks synthetic fibres into the ground for centuries. The clothing that reaches the ocean fragments into particles now found in the tissues of marine animals and in human blood.</p>

<h3>What the Alternatives Look Like</h3>
<p>Natural fibres — organic cotton, linen, hemp, wool — biodegrade and do not shed persistent microplastics. Second-hand clothing platforms extend garment lifespans by an average of 2.2 years, halving their carbon footprint. Clothing repair, alteration, and upcycling keep textiles in use and out of waste streams. Buying significantly less, but buying better, is the most effective individual action.</p>

<p>The fashion industry''s waste problem will not be solved by consumer behaviour alone. But consumer behaviour shapes demand, and demand shapes what gets produced.</p>',
NULL, 'Kavita Menon', 'kavita@example.com', 'approved',
'Well-researched, important environmental issue covered thoroughly. Approved.', 'admin', NOW()),

('Plastic-Free July: What I Learned After 31 Days',
'<p>Plastic-Free July is a global challenge: avoid single-use plastic for the entire month of July. I took part for the first time last year, expecting it to be difficult. It was — but not in the ways I anticipated. Here is an honest account of what changed, what did not, and what I am still doing eight months later.</p>

<h3>Day One: The Audit</h3>
<p>Before the month began, I did a single-use plastic audit of my morning routine. By 9am, I had already encountered plastic in my toothbrush packaging, the cap of my shampoo, the wrapper on my soap, a plastic milk bag, a coffee cup lid, and the packaging of my breakfast cereal. The scale of the problem became immediately, viscerally clear.</p>

<h3>Easy Wins</h3>
<p>The substitutions that required almost no effort: a reusable water bottle (already owned), a cloth bag for shopping (already owned), loose-leaf tea instead of teabags, a bar of soap instead of liquid soap in a plastic pump bottle, and a bamboo toothbrush. Combined, these changes eliminated perhaps a dozen pieces of single-use plastic daily.</p>

<h3>The Hard Parts</h3>
<p>Fresh vegetables at the supermarket almost all come pre-packaged. Meat and fish at any non-specialist counter come on polystyrene trays. Medications come in blister packs. Electronics come wrapped in metres of bubble wrap and foam. Some plastic is genuinely difficult to avoid without restructuring how you shop entirely.</p>

<h3>What Persisted</h3>
<p>Eight months later, I still use the reusable bottle, the cloth bags, the bar soap, the bamboo toothbrush. I shop at the farmers market for produce. I buy bulk grains. My plastic output is probably a third of what it was. The challenge gave me enough momentum to make changes that became permanent habits.</p>',
NULL, 'Rohit Varma', 'rohit@example.com', 'approved',
'First-person narrative, highly engaging, directly on-topic. Approved.', 'admin', NOW()),

('A Beginner''s Guide to Community Waste Audits',
'<p>A waste audit is exactly what it sounds like: a systematic analysis of what a household, office, school, or community is throwing away. The data collected from a waste audit is the most reliable basis for designing waste reduction strategies, because it replaces assumptions with evidence.</p>

<h3>Why Audits Work</h3>
<p>Most people significantly underestimate how much they waste and have incorrect assumptions about what categories dominate their waste stream. The act of sorting, weighing, and categorising creates an emotional engagement with the data that reading statistics about national waste averages does not. When you hold the plastic you have generated in a single week in your hands, it lands differently.</p>

<h3>Running a Simple Household Audit</h3>
<p>You need: rubber gloves, a tarpaulin or large plastic sheet, a scale, a notebook, and one week of collected waste. On audit day, sort everything into categories: food waste, paper, cardboard, glass, metals, plastics (separated by resin code), textiles, and residual waste. Weigh each category. Photograph the sorted piles. Record the data.</p>

<h3>What to Do with the Results</h3>
<p>Identify the two or three largest categories by weight. These are your intervention priorities. For most households, food waste and plastic packaging dominate. For offices, paper and single-use packaging are typically the largest streams. Design one specific behaviour change for each priority category. Audit again in three months to measure progress.</p>

<h3>Scaling Up: Community Audits</h3>
<p>A community audit follows the same methodology but requires coordination across multiple households, clear communication protocols, and a central sorting location. The data generated is useful to local government, waste management operators, and environmental organisations. Several Indian cities have used community-led waste audits to design more effective collection and processing infrastructure.</p>',
NULL, 'Nandini Pillai', 'nandini@example.com', 'approved',
'Educational, actionable, perfectly on-topic. Approved.', 'admin', NOW());
