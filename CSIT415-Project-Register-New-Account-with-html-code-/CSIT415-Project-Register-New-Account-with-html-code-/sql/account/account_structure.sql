CREATE TABLE Users (
    userID INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each user
    username VARCHAR(50) NOT NULL,         -- Username (up to 50 characters)
    password VARCHAR(255) NOT NULL,        -- Password (up to 255 characters)
    isAdmin BOOLEAN NOT NULL DEFAULT 0,     -- Boolean to indicate admin status (default is false)
    finesDue INT NOT NULL DEFAULT 0, -- Fines due (default is 0)
    holdActive BOOLEAN NOT NULL DEFAULT 0 -- Boolean to indicate if a hold is active (default is false)
);
