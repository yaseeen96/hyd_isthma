# Use Node.js as the base image
FROM node:21.7.2-alpine

# Set the working directory in the container
WORKDIR /app

# Copy package.json and package-lock.json to the container
COPY package.json package-lock.json ./

# Install dependencies
RUN npm ci

# Copy the app's source code to the container
COPY . .

# Build the React app with Vite
RUN npm run build

# Install serve to serve the build
RUN npm install -g serve

# Expose the port that the app runs on
EXPOSE 8080

# Serve the build with serve
CMD ["serve", "-s", "dist", "-l", "8080"]
