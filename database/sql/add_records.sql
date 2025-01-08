use newsviet;
INSERT INTO users (name, email, password, role)
VALUES 
('User 1', 'user1@example.com', 'hashed_password', 'reader'),
('User 2', 'user2@example.com', 'hashed_password', 'author'),
('User 3', 'user3@example.com', 'hashed_password', 'reader'),
('User 4', 'user4@example.com', 'hashed_password', 'author'),
('User 5', 'user5@example.com', 'hashed_password', 'reader'),
('User 6', 'user6@example.com', 'hashed_password', 'admin'),
('User 7', 'user7@example.com', 'hashed_password', 'reader'),
('User 8', 'user8@example.com', 'hashed_password', 'admin'),
('User 9', 'user9@example.com', 'hashed_password', 'author'),
('User 10', 'user10@example.com', 'hashed_password', 'reader');

-- Thêm các danh mục cha
INSERT INTO categories (name, description, parent_id, is_active)
VALUES 
('Technology', 'Articles about technology', NULL, 1),
('Health', 'Articles about health', NULL, 1),
('Lifestyle', 'Articles about lifestyle', NULL, 1),
('Business', 'Business-related articles', NULL, 1);

-- Thêm các danh mục con cho từng danh mục cha
INSERT INTO categories (name, description, parent_id, is_active)
VALUES
('Smartphones', 'Smartphones and gadgets', 1, 1),
('AI', 'Artificial Intelligence articles', 1, 1),
('Nutrition', 'Nutrition-related articles', 2, 1),
('Mental Health', 'Mental health and well-being', 2, 1),
('Fitness', 'Fitness and workout articles', 3, 1),
('Yoga', 'Yoga-related articles', 3, 1),
('Investing', 'Investment strategies', 4, 1),
('Economy', 'Economy-related articles', 4, 1);

-- Thêm danh mục con lồng nhau
INSERT INTO categories (name, description, parent_id, is_active)
VALUES
('5G Technology', '5G advancements in mobile tech', 5, 1),
('Deep Learning', 'Deep learning technologies and research', 6, 1);

INSERT INTO articles (title, content, image, category_id, author_id)
VALUES
('Article 1', 'Content of Article 1', 'image1.jpg', 1, 1),
('Article 2', 'Content of Article 2', 'image2.jpg', 2, 2),
('Article 3', 'Content of Article 3', 'image3.jpg', 3, 3),
('Article 4', 'Content of Article 4', 'image4.jpg', 4, 4),
('Article 5', 'Content of Article 5', 'image5.jpg', 5, 5),
('Article 6', 'Content of Article 6', 'image6.jpg', 6, 6),
('Article 7', 'Content of Article 7', 'image7.jpg', 7, 7),
('Article 8', 'Content of Article 8', 'image8.jpg', 8, 8),
('Article 9', 'Content of Article 9', 'image9.jpg', 9, 9),
('Article 10', 'Content of Article 10', 'image10.jpg', 10, 10);

INSERT INTO tags (name)
VALUES 
('Tech'),
('Health'),
('Lifestyle'),
('Business'),
('Education'),
('Sports'),
('Science'),
('Entertainment'),
('Politics'),
('Culture');


INSERT INTO article_tag (article_id, tag_id)
VALUES
(1, 1), (2, 2), (3, 3), (4, 4), (5, 5),
(6, 6), (7, 7), (8, 8), (9, 9), (10, 10);


INSERT INTO comments (content, article_id, user_id)
VALUES
('Great article!', 1, 1),
('Very informative', 2, 2),
('Loved it!', 3, 3),
('Amazing content', 4, 4),
('Interesting points', 5, 5),
('Good read', 6, 6),
('Nice article', 7, 7),
('Great work!', 8, 8),
('Well written', 9, 9),
('Impressive', 10, 10);