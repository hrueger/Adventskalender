import { Router } from "express";
import AuthController from "../controllers/AuthController";

const router = Router();
// Login route
router.post("/login", AuthController.login);
router.post("/renewToken", AuthController.renewToken);
router.post("/resetPassword", AuthController.sendPasswordResetMail);
router.post("/newPassword/:resetToken", AuthController.setNewPassword);

export default router;
