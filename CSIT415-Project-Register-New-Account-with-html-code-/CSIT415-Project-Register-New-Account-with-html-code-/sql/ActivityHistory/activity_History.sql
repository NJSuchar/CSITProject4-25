CREATE TABLE ActivityHistory (
    activityID INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each activity
    userID INT NOT NULL,                       -- Foreign key referencing Users table
    bookID INT,                            -- Foreign key referencing Books table (if applicable)
    activityType VARCHAR(50) NOT NULL,        -- Type of activity (e.g., login, logout)
    activityTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP, -- Timestamp of the activity
    dueDate DATETIME DEFAULT null,
    FOREIGN KEY (userID) REFERENCES Users(userID) -- Foreign key constraint
    FOREIGN KEY (bookID) REFERENCES Books(id) -- Foreign key constraint (if applicable)
);