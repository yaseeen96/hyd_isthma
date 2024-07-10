import { logEvent } from 'firebase/analytics';

// This function sends a 'select_content' event to Firebase Analytics
//
// Parameters:
// - analytics: The initialized Firebase Analytics instance
// - contentType: A string representing the type of content (e.g., 'article', 'video', 'image')
// - itemId: A unique identifier for the content item
// - itemName: The name or title of the content item

export function trackSelectContent(analytics, contentType, itemId, itemName) {
    logEvent(analytics, 'select_content', {
        content_type: contentType,
        item_id: itemId,
        item_name: itemName,
    });
}
