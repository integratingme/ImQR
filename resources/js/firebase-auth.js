/**
 * Firebase Authentication Module
 *
 * Handles Firebase Auth initialization and provides methods for
 * email/password and Google authentication.
 * After successful Firebase auth, sends the ID token to Laravel
 * backend to establish a session.
 */

import { initializeApp } from 'firebase/app';
import {
    getAuth,
    signInWithEmailAndPassword,
    createUserWithEmailAndPassword,
    signInWithPopup,
    GoogleAuthProvider,
    signOut,
    onAuthStateChanged,
} from 'firebase/auth';

// Firebase configuration from Vite env variables
const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
    measurementId: import.meta.env.VITE_FIREBASE_MEASUREMENT_ID,
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const googleProvider = new GoogleAuthProvider();

/**
 * Send Firebase ID token to Laravel backend to create a session.
 *
 * @param {string} idToken - Firebase ID token
 * @param {string|null} name - User's display name (for registration)
 * @returns {Promise<object>} Backend response
 */
async function sendTokenToBackend(idToken, name = null) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const body = { id_token: idToken };
    if (name) {
        body.name = name;
    }

    const response = await fetch('/auth/firebase/callback', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: JSON.stringify(body),
    });

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || 'Authentication failed.');
    }

    return data;
}

/**
 * Sign in with email and password.
 */
async function signInWithEmail(email, password) {
    const userCredential = await signInWithEmailAndPassword(auth, email, password);
    const idToken = await userCredential.user.getIdToken();
    return sendTokenToBackend(idToken);
}

/**
 * Sign up with email and password.
 */
async function signUpWithEmail(email, password, name = null) {
    const userCredential = await createUserWithEmailAndPassword(auth, email, password);
    const idToken = await userCredential.user.getIdToken();
    return sendTokenToBackend(idToken, name);
}

/**
 * Sign in with Google popup.
 */
async function signInWithGoogle(name = null) {
    const userCredential = await signInWithPopup(auth, googleProvider);
    const idToken = await userCredential.user.getIdToken();
    const displayName = userCredential.user.displayName || name;
    return sendTokenToBackend(idToken, displayName);
}

/**
 * Sign out from Firebase and Laravel.
 * Redirects to logout page which handles Laravel logout.
 */
async function firebaseSignOut() {
    // Sign out from Firebase (non-blocking)
    signOut(auth).catch(error => {
        console.error('Firebase logout error:', error);
    });

    // Redirect to logout page which will handle Laravel logout
    window.location.href = '/logout';
}

/**
 * Get friendly error message from Firebase error code.
 */
function getErrorMessage(error) {
    const code = error?.code || '';

    const messages = {
        'auth/email-already-in-use': 'An account with this email already exists. Try logging in instead.',
        'auth/invalid-email': 'Please enter a valid email address.',
        'auth/operation-not-allowed': 'This sign-in method is not enabled.',
        'auth/weak-password': 'Password must be at least 6 characters.',
        'auth/user-disabled': 'This account has been disabled.',
        'auth/user-not-found': 'No account found with this email. Try registering first.',
        'auth/wrong-password': 'Incorrect password. Please try again.',
        'auth/invalid-credential': 'Invalid email or password. Please try again.',
        'auth/too-many-requests': 'Too many attempts. Please try again later.',
        'auth/popup-closed-by-user': 'Sign-in popup was closed. Please try again.',
        'auth/popup-blocked': 'Sign-in popup was blocked. Please allow popups for this site.',
    };

    return messages[code] || error?.message || 'An unexpected error occurred. Please try again.';
}

// Expose globally for use in Blade views
window.FirebaseAuth = {
    auth,
    signInWithEmail,
    signUpWithEmail,
    signInWithGoogle,
    signOut: firebaseSignOut,
    getErrorMessage,
    onAuthStateChanged: (callback) => onAuthStateChanged(auth, callback),
};

export {
    signInWithEmail,
    signUpWithEmail,
    signInWithGoogle,
    firebaseSignOut as signOut,
    getErrorMessage,
};
