# Use Node.js as the base image
FROM node:21.7.2-alpine

# Set the working directory in the container
WORKDIR /app

# Copy package.json and yarn.lock to the container
COPY package.json yarn.lock ./

# Install dependencies
RUN yarn install --frozen-lockfile

# Copy the app's source code to the container
COPY . .

# Build the React app with Vite
RUN yarn build

# Install serve to serve the build
RUN yarn global add serve

# Expose the port that the app runs on
EXPOSE 5000

# Serve the build with serve
CMD ["serve", "-s", "dist", "-l", "5000"]