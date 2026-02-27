# SSO Setup Guide

Complete setup instructions for enabling Single Sign-On with **Azure AD**, **Google Workspace**, and **Zoho**.

---

## Prerequisites

- AIHRM must be accessible via HTTPS (SSO providers require secure callback URLs)
- Admin access to the respective provider's developer console
- Your application's base URL (e.g., `https://hrm.yourcompany.com`)

---

## 1. Microsoft Azure AD

### Step 1: Register an Application in Azure Portal

1. Go to [Azure Portal](https://portal.azure.com)
2. Navigate to **Azure Active Directory** → **App registrations** → **New registration**
3. Fill in:
   - **Name**: `AIHRM` (or your company name)
   - **Supported account types**: Choose one:
     - "Accounts in this organizational directory only" (single-tenant)
     - "Accounts in any organizational directory" (multi-tenant)
   - **Redirect URI**: Select **Web** and enter:
     ```
     https://your-domain.com/auth/azure/callback
     ```
4. Click **Register**

### Step 2: Get Credentials

1. Copy the **Application (client) ID** → This is your **Client ID**
2. Copy the **Directory (tenant) ID** → This is your **Tenant ID**
3. Go to **Certificates & secrets** → **New client secret**
   - Add a description (e.g., "AIHRM SSO")
   - Set expiry (recommended: 24 months)
   - Click **Add** and immediately copy the **Value** → This is your **Client Secret**

### Step 3: API Permissions

1. Go to **API permissions** → **Add a permission**
2. Select **Microsoft Graph** → **Delegated permissions**
3. Add: `User.Read`, `email`, `profile`, `openid`
4. Click **Grant admin consent** for your organization

### Step 4: Configure in AIHRM

1. Go to **System Settings** → **Single Sign-On (SSO)**
2. Under **Microsoft Azure AD**:
   - Set to **Enabled**
   - Enter your **Client ID**, **Client Secret**, and **Tenant ID**
3. Click **Save Settings**

### Environment Variables (Alternative)

You can also set these in `.env`:
```env
AZURE_CLIENT_ID=your-client-id
AZURE_CLIENT_SECRET=your-client-secret
AZURE_TENANT_ID=your-tenant-id
AZURE_REDIRECT_URI=https://your-domain.com/auth/azure/callback
```

---

## 2. Google Workspace

### Step 1: Create OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project (or select existing)
3. Go to **APIs & Services** → **Credentials** → **Create Credentials** → **OAuth client ID**
4. If prompted, configure the **OAuth consent screen**:
   - User Type: **Internal** (for Google Workspace) or **External**
   - App name: `AIHRM`
   - Support email: your admin email
   - Developer contact: your email
5. For the OAuth client:
   - Application type: **Web application**
   - Name: `AIHRM SSO`
   - Authorized redirect URIs: Add:
     ```
     https://your-domain.com/auth/google/callback
     ```
6. Click **Create**

### Step 2: Get Credentials

- Copy the **Client ID** (ends in `.apps.googleusercontent.com`)
- Copy the **Client Secret**

### Step 3: Enable Required APIs

1. Go to **APIs & Services** → **Library**
2. Search and enable: **Google People API**

### Step 4: Configure in AIHRM

1. Go to **System Settings** → **Single Sign-On (SSO)**
2. Under **Google Workspace**:
   - Set to **Enabled**
   - Enter your **Client ID** and **Client Secret**
3. Click **Save Settings**

### Environment Variables (Alternative)
```env
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=https://your-domain.com/auth/google/callback
```

---

## 3. Zoho

### Step 1: Register a Client

1. Go to [Zoho API Console](https://api-console.zoho.com)
2. Click **Add Client** → **Server-based Applications**
3. Fill in:
   - **Client Name**: `AIHRM`
   - **Homepage URL**: `https://your-domain.com`
   - **Authorized Redirect URIs**:
     ```
     https://your-domain.com/auth/zoho/callback
     ```
4. Click **Create**

### Step 2: Get Credentials

- Copy the **Client ID**
- Copy the **Client Secret**

### Step 3: Configure in AIHRM

1. Go to **System Settings** → **Single Sign-On (SSO)**
2. Under **Zoho Workspace**:
   - Set to **Enabled**
   - Enter your **Client ID** and **Client Secret**
3. Click **Save Settings**

### Environment Variables (Alternative)
```env
ZOHO_CLIENT_ID=your-client-id
ZOHO_CLIENT_SECRET=your-client-secret
ZOHO_REDIRECT_URI=https://your-domain.com/auth/zoho/callback
```

---

## Registration Policy

In **System Settings → SSO**, you can control the **"Allow new user registration via SSO"** toggle:

| Setting | Behavior |
|---------|----------|
| **No** (default) | Only employees already in the system can log in via SSO. New users see an error. |
| **Yes** | New accounts are created automatically via SSO. They get the **Employee** role. |

**Recommendation**: Keep set to **No** for corporate environments. Add employees via Admin first.

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| "Invalid SSO provider" | Provider not enabled in Settings |
| "SSO Login failed" | Verify client ID/secret and redirect URI match exactly |
| Redirect URI mismatch | Callback URL must be: `https://your-domain.com/auth/{provider}/callback` |
| "No employee account found" | Enable registration or add the employee first |
| Tokens expire | Check Azure Portal for secret expiry dates |
