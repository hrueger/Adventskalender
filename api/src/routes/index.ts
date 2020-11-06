import { Router } from "express";
import auth from "./auth";
import users from "./users";
import tasks from "./tasks";

const routes = Router();

routes.use("/auth", auth);
routes.use("/users", users);
routes.use("/tasks", tasks);

export default routes;
