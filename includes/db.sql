-- =====================================
-- QUIZ WEBSITE DATABASE (FULL VERSION)
-- =====================================

DROP DATABASE IF EXISTS quiz_website;
CREATE DATABASE quiz_website;
USE quiz_website;

-- 1️⃣ USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- 2️⃣ CATEGORIES TABLE
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- 3️⃣ QUESTIONS TABLE
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    question_text TEXT NOT NULL,
    correct_answer VARCHAR(255) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 4️⃣ ANSWERS TABLE
CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    answer_text VARCHAR(255) NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- 5️⃣ RESULTS TABLE
CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    score INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 6️⃣ DEFAULT ADMIN USER (password = admin123)
INSERT INTO users (username, password) VALUES (
    'admin',
    '$2y$10$u7NfLwqKp6A3yYvUf2eOeO5vHhOTpN7OQ8fhc6bkzC3YhWq7p7Z5a'
);

-- 7️⃣ CATEGORIES
INSERT INTO categories (name) VALUES
('General Knowledge'),
('Science'),
('History'),
('Sports'),
('Geography'),
('Technology'),
('Movies'),
('Music'),
('Literature'),
('Mathematics');

-- =====================================
-- 8️⃣ QUESTIONS + ANSWERS
-- =====================================

-- CATEGORY 1: General Knowledge
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(1, 'What is the capital of France?', 'Paris'),
(1, 'Which planet is known as the Red Planet?', 'Mars'),
(1, 'What color are emeralds?', 'Green'),
(1, 'Which ocean is the largest?', 'Pacific'),
(1, 'How many continents are there?', '7');

INSERT INTO answers (question_id, answer_text) VALUES
(1, 'Paris'), (1, 'London'), (1, 'Rome'), (1, 'Berlin'),
(2, 'Earth'), (2, 'Venus'), (2, 'Mars'), (2, 'Jupiter'),
(3, 'Red'), (3, 'Green'), (3, 'Blue'), (3, 'Yellow'),
(4, 'Indian'), (4, 'Atlantic'), (4, 'Pacific'), (4, 'Arctic'),
(5, '5'), (5, '6'), (5, '7'), (5, '8');

-- CATEGORY 2: Science
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(2, 'What gas do plants absorb from the atmosphere?', 'Carbon Dioxide'),
(2, 'What is the chemical symbol for water?', 'H2O'),
(2, 'Who developed the theory of relativity?', 'Albert Einstein'),
(2, 'Which planet has the most moons?', 'Saturn'),
(2, 'What is the center of an atom called?', 'Nucleus');

INSERT INTO answers (question_id, answer_text) VALUES
(6, 'Oxygen'), (6, 'Nitrogen'), (6, 'Carbon Dioxide'), (6, 'Helium'),
(7, 'HO2'), (7, 'H2O'), (7, 'O2H'), (7, 'HHO'),
(8, 'Isaac Newton'), (8, 'Albert Einstein'), (8, 'Galileo'), (8, 'Nikola Tesla'),
(9, 'Earth'), (9, 'Mars'), (9, 'Saturn'), (9, 'Jupiter'),
(10, 'Nucleus'), (10, 'Proton'), (10, 'Electron'), (10, 'Neutron');

-- CATEGORY 3: History
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(3, 'Who was the first president of the United States?', 'George Washington'),
(3, 'In which year did World War II end?', '1945'),
(3, 'Who built the Great Wall of China?', 'Qin Shi Huang'),
(3, 'Which empire was ruled by Julius Caesar?', 'Roman Empire'),
(3, 'Who was known as the Maid of Orleans?', 'Joan of Arc');

INSERT INTO answers (question_id, answer_text) VALUES
(11, 'Abraham Lincoln'), (11, 'George Washington'), (11, 'John Adams'), (11, 'Thomas Jefferson'),
(12, '1940'), (12, '1945'), (12, '1950'), (12, '1939'),
(13, 'Genghis Khan'), (13, 'Qin Shi Huang'), (13, 'Sun Tzu'), (13, 'Kublai Khan'),
(14, 'Greek Empire'), (14, 'Roman Empire'), (14, 'Ottoman Empire'), (14, 'British Empire'),
(15, 'Cleopatra'), (15, 'Joan of Arc'), (15, 'Marie Curie'), (15, 'Queen Victoria');

-- CATEGORY 4: Sports
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(4, 'How many players are there in a football team?', '11'),
(4, 'Which sport uses a shuttlecock?', 'Badminton'),
(4, 'Who has won the most Olympic gold medals?', 'Michael Phelps'),
(4, 'Where were the 2016 Olympics held?', 'Rio de Janeiro'),
(4, 'What is the national sport of Japan?', 'Sumo Wrestling');

INSERT INTO answers (question_id, answer_text) VALUES
(16, '10'), (16, '11'), (16, '12'), (16, '9'),
(17, 'Tennis'), (17, 'Badminton'), (17, 'Table Tennis'), (17, 'Squash'),
(18, 'Usain Bolt'), (18, 'Michael Phelps'), (18, 'Simone Biles'), (18, 'Carl Lewis'),
(19, 'Tokyo'), (19, 'Beijing'), (19, 'Rio de Janeiro'), (19, 'London'),
(20, 'Karate'), (20, 'Judo'), (20, 'Sumo Wrestling'), (20, 'Kendo');

-- CATEGORY 5: Geography
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(5, 'Which is the largest desert in the world?', 'Sahara'),
(5, 'Which country has the most population?', 'China'),
(5, 'What is the longest river in the world?', 'Nile'),
(5, 'Which continent is Egypt located in?', 'Africa'),
(5, 'What is the capital of Australia?', 'Canberra');

INSERT INTO answers (question_id, answer_text) VALUES
(21, 'Sahara'), (21, 'Arabian'), (21, 'Gobi'), (21, 'Kalahari'),
(22, 'China'), (22, 'India'), (22, 'USA'), (22, 'Russia'),
(23, 'Amazon'), (23, 'Nile'), (23, 'Yangtze'), (23, 'Mississippi'),
(24, 'Asia'), (24, 'Africa'), (24, 'Europe'), (24, 'South America'),
(25, 'Sydney'), (25, 'Melbourne'), (25, 'Canberra'), (25, 'Brisbane');

-- CATEGORY 6: Technology
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(6, 'Who founded Microsoft?', 'Bill Gates'),
(6, 'What does CPU stand for?', 'Central Processing Unit'),
(6, 'Which company created the iPhone?', 'Apple'),
(6, 'What does HTML stand for?', 'HyperText Markup Language'),
(6, 'Which language is used for Android development?', 'Java');

INSERT INTO answers (question_id, answer_text) VALUES
(26, 'Steve Jobs'), (26, 'Bill Gates'), (26, 'Elon Musk'), (26, 'Mark Zuckerberg'),
(27, 'Central Processing Unit'), (27, 'Central Power Unit'), (27, 'Control Processing Unit'), (27, 'Computer Power Unit'),
(28, 'Apple'), (28, 'Google'), (28, 'Microsoft'), (28, 'Samsung'),
(29, 'HyperText Markup Language'), (29, 'HighText Markup Language'), (29, 'Hyper Transfer Markup Language'), (29, 'HyperText Multi Language'),
(30, 'C++'), (30, 'Python'), (30, 'Java'), (30, 'Swift');

-- CATEGORY 7: Movies
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(7, 'Who directed Titanic?', 'James Cameron'),
(7, 'Which movie features the character "Harry Potter"?', 'Harry Potter'),
(7, 'Which actor played Iron Man?', 'Robert Downey Jr.'),
(7, 'Which movie won Best Picture in 2020?', 'Parasite'),
(7, 'Which movie features the quote "I’ll be back"?', 'The Terminator');

INSERT INTO answers (question_id, answer_text) VALUES
(31, 'Steven Spielberg'), (31, 'James Cameron'), (31, 'Christopher Nolan'), (31, 'Martin Scorsese'),
(32, 'The Hobbit'), (32, 'Harry Potter'), (32, 'Twilight'), (32, 'Narnia'),
(33, 'Chris Evans'), (33, 'Robert Downey Jr.'), (33, 'Mark Ruffalo'), (33, 'Tom Holland'),
(34, 'Joker'), (34, '1917'), (34, 'Parasite'), (34, 'Ford v Ferrari'),
(35, 'The Matrix'), (35, 'The Terminator'), (35, 'Predator'), (35, 'Die Hard');

-- CATEGORY 8: Music
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(8, 'Who is known as the King of Pop?', 'Michael Jackson'),
(8, 'Which band wrote "Hey Jude"?', 'The Beatles'),
(8, 'Who sang "Shape of You"?', 'Ed Sheeran'),
(8, 'Which instrument has 88 keys?', 'Piano'),
(8, 'Who is the lead singer of Coldplay?', 'Chris Martin');

INSERT INTO answers (question_id, answer_text) VALUES
(36, 'Elvis Presley'), (36, 'Michael Jackson'), (36, 'Prince'), (36, 'Freddie Mercury'),
(37, 'The Beatles'), (37, 'Queen'), (37, 'ABBA'), (37, 'Pink Floyd'),
(38, 'Ed Sheeran'), (38, 'Justin Bieber'), (38, 'Shawn Mendes'), (38, 'Bruno Mars'),
(39, 'Guitar'), (39, 'Piano'), (39, 'Violin'), (39, 'Flute'),
(40, 'Chris Martin'), (40, 'Adam Levine'), (40, 'Bono'), (40, 'Dave Grohl');

-- CATEGORY 9: Literature
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(9, 'Who wrote "Romeo and Juliet"?', 'William Shakespeare'),
(9, 'Who wrote "Harry Potter"?', 'J.K. Rowling'),
(9, 'What is the main character in "The Hobbit"?', 'Bilbo Baggins'),
(9, 'Who wrote "Pride and Prejudice"?', 'Jane Austen'),
(9, 'Who is the author of "1984"?', 'George Orwell');

INSERT INTO answers (question_id, answer_text) VALUES
(41, 'Charles Dickens'), (41, 'William Shakespeare'), (41, 'Leo Tolstoy'), (41, 'Mark Twain'),
(42, 'Suzanne Collins'), (42, 'J.K. Rowling'), (42, 'Stephen King'), (42, 'Rick Riordan'),
(43, 'Bilbo Baggins'), (43, 'Frodo Baggins'), (43, 'Gandalf'), (43, 'Thorin'),
(44, 'Emily Bronte'), (44, 'Jane Austen'), (44, 'Mary Shelley'), (44, 'Louisa May Alcott'),
(45, 'Aldous Huxley'), (45, 'George Orwell'), (45, 'J.R.R. Tolkien'), (45, 'C.S. Lewis');

-- CATEGORY 10: Mathematics
INSERT INTO questions (category_id, question_text, correct_answer) VALUES
(10, 'What is 12 × 8?', '96'),
(10, 'What is the square root of 81?', '9'),
(10, 'What is 15% of 200?', '30'),
(10, 'What is the value of π (approx)?', '3.14'),
(10, 'Solve: 5² = ?', '25');

INSERT INTO answers (question_id, answer_text) VALUES
(46, '94'), (46, '96'), (46, '98'), (46, '100'),
(47, '8'), (47, '9'), (47, '10'), (47, '7'),
(48, '25'), (48, '30'), (48, '35'), (48, '40'),
(49, '3.14'), (49, '3.15'), (49, '3.16'), (49, '3.13'),
(50, '20'), (50, '25'), (50, '30'), (50, '15');
