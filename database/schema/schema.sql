-- ----------------------------
-- Table structure for image
-- ----------------------------
DROP TABLE IF EXISTS "image";
CREATE TABLE "image" (
"id" uuid NOT NULL,
"recipe_id" uuid,
"file" varchar(255) COLLATE "default" NOT NULL,
"temporary" bool DEFAULT true NOT NULL,
"created_at" timestamptz(6) NOT NULL
);

-- ----------------------------
-- Table structure for recipe
-- ----------------------------
DROP TABLE IF EXISTS "recipe";
CREATE TABLE "recipe" (
"id" uuid NOT NULL,
"user_id" uuid NOT NULL,
"title" varchar(255) COLLATE "default" NOT NULL,
"description" text COLLATE "default" NOT NULL,
"created_at" timestamptz(6) NOT NULL
);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS "user";
CREATE TABLE "user" (
"id" uuid NOT NULL,
"username" varchar(255) COLLATE "default" NOT NULL,
"password" varchar(255) COLLATE "default" NOT NULL,
"created_at" timestamptz(6) NOT NULL
);


-- ----------------------------
-- Primary Key structure for table recipe
-- ----------------------------
ALTER TABLE "recipe" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table user
-- ----------------------------
ALTER TABLE "user" ADD UNIQUE ("username");

-- ----------------------------
-- Primary Key structure for table user
-- ----------------------------
ALTER TABLE "user" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Foreign Key structure for table "image"
-- ----------------------------
ALTER TABLE "image" ADD FOREIGN KEY ("recipe_id") REFERENCES "recipe" ("id") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Key structure for table "recipe"
-- ----------------------------
ALTER TABLE "recipe" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("id") ON DELETE CASCADE ON UPDATE CASCADE;